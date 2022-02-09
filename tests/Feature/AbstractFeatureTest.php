<?php

namespace ConsulConfigManager\Tasks\Test\Feature;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use ConsulConfigManager\Tasks\Models\Task;
use ConsulConfigManager\Tasks\Models\Action;
use ConsulConfigManager\Tasks\Test\TestCase;
use Illuminate\Database\Eloquent\Collection;
use ConsulConfigManager\Tasks\Enums\TaskType;
use ConsulConfigManager\Tasks\Models\Pipeline;
use ConsulConfigManager\Tasks\Enums\ActionType;
use ConsulConfigManager\Tasks\Models\TaskAction;
use ConsulConfigManager\Tasks\Models\PipelineTask;
use ConsulConfigManager\Tasks\Enums\ExecutionState;
use ConsulConfigManager\Tasks\Models\TaskExecution;
use ConsulConfigManager\Tasks\Models\ActionExecution;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Models\PipelineExecution;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskActionInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionInterface;
use ConsulConfigManager\Consul\Agent\Interfaces\ServiceInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionInterface;
use ConsulConfigManager\Consul\Agent\Interfaces\ServiceRepositoryInterface;

/**
 * Class AbstractFeatureTest
 * @package ConsulConfigManager\Tasks\Test\Feature
 */
abstract class AbstractFeatureTest extends TestCase
{
    /**
     * Server identifier for execution
     * @var string
     */
    protected string $serverIdentifier = "a2ec83ed-2a05-437a-b438-58559eaa7ffe";

    /**
     * Action identifier for execution
     * @var string
     */
    protected string $actionIdentifier = "ba6addfc-c524-415e-8f68-b60dd1146840";

    /**
     * Task identifier for execution
     * @var string
     */
    protected string $taskIdentifier = "55dfebdd-a386-482c-a16b-cd2afa4e50cb";

    /**
     * Pipeline identifier for execution
     * @var string
     */
    protected string $pipelineIdentifier = "ec019e70-ec3d-4ecd-b744-7870b4fbc7a6";

    /**
     * Execution identifier for execution
     * @var string
     */
    protected string $executionIdentifier = "c4f935d8-f539-4207-9824-1e6e2da6a211";

    /**
     * Create service through repository and return its instance
     * @return ServiceInterface
     * @throws BindingResolutionException
     */
    protected function createServiceThroughRepository(): ServiceInterface
    {
        $result = app()->make(ServiceRepositoryInterface::class)->create([
            'id'                =>  'ccm-example.local-127.0.0.1',
            'service'           =>  'ccm',
            'tags'              =>  [],
            'meta'              =>  [
                'environment'   =>  'testing',
            ],
            'port'              =>  32175,
            'address'           =>  '127.0.0.1',
            'online'            =>  true,
            'datacenter'        =>  'leads',
        ]);
        $this->serverIdentifier = $result->getUuid();
        return $result;
    }

    /**
     * Create and get new action execution
     * @param int $state
     * @return ActionExecutionInterface
     */
    protected function createAndGetActionExecution(int $state = ExecutionState::CREATED): ActionExecutionInterface
    {
        return ActionExecution::create([
            'server_uuid'               =>  $this->serverIdentifier,
            'action_uuid'               =>  $this->actionIdentifier,
            'task_uuid'                 =>  $this->taskIdentifier,
            'pipeline_uuid'             =>  $this->pipelineIdentifier,
            'pipeline_execution_uuid'   =>  $this->executionIdentifier,
            'state'                     =>  $state,
        ]);
    }

    /**
     * Create new action through HTTP request
     * @return TestResponse
     */
    protected function createAndGetAction(): TestResponse
    {
        $response = $this->post('/task-manager/actions', [
            'name'          =>  'Example Action',
            'description'   =>  'This is description for Example Action',
            'type'          =>  ActionType::REMOTE,
            'command'       =>  'lsb_release',
            'arguments'     =>  [
                '-a',
            ],
            'working_dir'   =>  null,
            'run_as'        =>  null,
            'use_sudo'      =>  false,
            'fail_on_error' =>  true,
        ]);

        $this->actionIdentifier = Arr::get($response->json('data'), 'uuid');

        return $response;
    }

    /**
     * Create new action model through HTTP request
     * @return ActionInterface
     */
    protected function createAndGetActionModel(): ActionInterface
    {
        return new Action($this->createAndGetAction()->json('data'));
    }

    /**
     * Create and get task execution
     * @return TaskExecutionInterface
     */
    protected function createAndGetTaskExecution(): TaskExecutionInterface
    {
        return TaskExecution::create([
            'task_uuid'                 =>  $this->taskIdentifier,
            'pipeline_uuid'             =>  $this->pipelineIdentifier,
            'pipeline_execution_uuid'   =>  $this->executionIdentifier,
            'state'                     =>  ExecutionState::CREATED,
        ]);
    }

    /**
     * Create new task through HTTP request
     * @return TestResponse
     */
    protected function createAndGetTask(): TestResponse
    {
        $response = $this->post('/task-manager/tasks', [
            'name'              =>  'Example Task',
            'description'       =>  'This is description for Example Task',
            'type'              =>  TaskType::REMOTE,
            'fail_on_error'     =>  false,
        ]);

        $this->taskIdentifier = Arr::get($response->json('data'), 'uuid');

        return $response;
    }

    /**
     * Create new task model through HTTP request
     * @return TaskInterface
     */
    protected function createAndGetTaskModel(): TaskInterface
    {
        return new Task($this->createAndGetTask()->json('data'));
    }

    /**
     * Create new task action through HTTP request
     * @param bool $failTaskCreation
     * @param bool $failActionCreation
     * @return TestResponse
     */
    protected function createAndGetTaskAction(bool $failTaskCreation = false, bool $failActionCreation = false): TestResponse
    {
        $task = $this->getTask($this->createAndGetTaskModel()->getUuid());
        $action = $this->getAction($this->createAndGetActionModel()->getUuid());

        if ($failTaskCreation) {
            $task->setUuid(Str::uuid()->toString());
        }

        if ($failActionCreation) {
            $action->setUuid(Str::uuid()->toString());
        }

        $response = $this->post('/task-manager/tasks/' . $task->getUuid() . '/actions', [
            'action_uuid'       =>  $action->getUuid(),
            'order'             =>  1,
        ]);

        if ($response->isSuccessful()) {
            $createdTask = $this->getTask($task->getUuid());
            $createdAction = $this->getAction($action->getUuid());
            $createdTaskAction = $this->getTaskAction($task, $action);

            $this->assertSame($createdTask->getUuid(), $createdTaskAction->getTaskUuid());
            $this->assertSame($createdAction->getUuid(), $createdTaskAction->getActionUuid());
        }

        return $response;
    }

    /**
     * Create new task action through HTTP request
     * @param bool $failTaskCreation
     * @param bool $failActionCreation
     * @return TestResponse
     */
    protected function createAndGetTaskActionWithIDs(bool $failTaskCreation = false, bool $failActionCreation = false): TestResponse
    {
        $task = $this->getTask($this->createAndGetTaskModel()->getUuid());
        $action = $this->getAction($this->createAndGetActionModel()->getUuid());

        if ($failTaskCreation) {
            $task->setID(2);
        }

        if ($failActionCreation) {
            $action->setID(2);
        }

        $response = $this->post('/task-manager/tasks/' . $task->getID() . '/actions', [
            'action_uuid'       =>  $action->getID(),
            'order'             =>  1,
        ]);

        if ($response->isSuccessful()) {
            $createdTask = $this->getTask($task->getUuid());
            $createdAction = $this->getAction($action->getUuid());
            $createdTaskAction = $this->getTaskAction($task, $action);

            $this->assertSame($createdTask->getUuid(), $createdTaskAction->getTaskUuid());
            $this->assertSame($createdAction->getUuid(), $createdTaskAction->getActionUuid());
        }

        return $response;
    }

    /**
     * Create new task action model through HTTP request
     * @param bool $failTaskCreation
     * @param bool $failActionCreation
     * @return TaskActionInterface
     */
    protected function createAndGetTaskActionModel(bool $failTaskCreation = false, bool $failActionCreation = false): TaskActionInterface
    {
        return new TaskAction($this->createAndGetTaskAction($failTaskCreation, $failActionCreation)->json('data'));
    }

    /**
     * Assert that created task action has same task as specified
     * @param string $identifier
     * @return void
     */
    protected function assertTaskActionHasSameTask(string $identifier): void
    {
        $this->assertSame($identifier, $this->getTaskAction()->getTaskUuid());
    }

    /**
     * Assert that created task action has same action as specified
     * @param string $identifier
     * @return void
     */
    protected function assertTaskActionHasSameAction(string $identifier): void
    {
        $this->assertSame($identifier, $this->getTaskAction()->getActionUuid());
    }

    /**
     * Assert that created task action has same order as specified
     * @param int $order
     * @return void
     */
    protected function assertTaskActionHasSameOrder(int $order): void
    {
        $this->assertSame($order, $this->getTaskAction()->getOrder());
    }

    /**
     * Get first action
     * @return ActionInterface
     */
    protected function getFirstAction(): ActionInterface
    {
        return Action::all()->first();
    }

    /**
     * Get action
     * @param string|int $identifier
     * @return ActionInterface|null
     */
    protected function getAction(string|int $identifier): ?ActionInterface
    {
        if (is_integer($identifier)) {
            return Action::where('id', '=', $identifier)->first();
        }
        return Action::where('uuid', '=', $identifier)->first();
    }

    /**
     * Get action identifier as integer
     * @param ActionInterface|null $action
     * @return string
     */
    protected function getActionIntegerIdentifier(?ActionInterface $action = null): string
    {
        if (is_null($action)) {
            $action = $this->getFirstAction();
        }
        return $action->getID();
    }

    /**
     * Get action identifier as string
     * @param ActionInterface|null $action
     * @return string
     */
    protected function getActionStringIdentifier(?ActionInterface $action = null): string
    {
        if (is_null($action)) {
            $action = $this->getFirstAction();
        }
        return $action->getUuid();
    }

    /**
     * Get first task
     * @return TaskInterface
     */
    protected function getFirstTask(): TaskInterface
    {
        return Task::all()->first();
    }

    /**
     * Get task
     * @param string|int $identifier
     * @return TaskInterface|null
     */
    protected function getTask(string|int $identifier): ?TaskInterface
    {
        if (is_integer($identifier)) {
            return Task::where('id', '=', $identifier)->first();
        }
        return Task::where('uuid', '=', $identifier)->first();
    }

    /**
     * Get task identifier as integer
     * @param TaskInterface|null $task
     * @return string
     */
    protected function getTaskIntegerIdentifier(?TaskInterface $task = null): string
    {
        if (is_null($task)) {
            $task = $this->getFirstTask();
        }
        return $task->getID();
    }

    /**
     * Get task identifier as string
     * @param TaskInterface|null $task
     * @return string
     */
    protected function getTaskStringIdentifier(?TaskInterface $task = null): string
    {
        if (is_null($task)) {
            $task = $this->getFirstTask();
        }
        return $task->getUuid();
    }

    /**
     * Get task action
     * @param TaskInterface|null $task
     * @param ActionInterface|null $action
     * @return TaskActionInterface|null
     */
    protected function getTaskAction(?TaskInterface $task = null, ?ActionInterface $action = null): ?TaskActionInterface
    {
        return TaskAction::where('task_uuid', '=', $this->getTaskStringIdentifier($task))
            ->where('action_uuid', '=', $this->getActionStringIdentifier($action))
            ->first();
    }

    /**
     * Get task actions
     * @param TaskInterface|array|null $task
     * @return Collection
     */
    protected function getTaskActions(TaskInterface|array|null $task = null): Collection
    {
        if (!$task) {
            return $this->createAndGetTaskModel()->actions;
        }
        if (is_array($task)) {
            return (new Task($task))->actions;
        }
        return $task->actions;
    }

    /**
     * Create and get pipeline execution
     * @return PipelineExecutionInterface
     */
    protected function createAndGetPipelineExecution(): PipelineExecutionInterface
    {
        return PipelineExecution::create([
            'uuid'          =>  $this->executionIdentifier,
            'pipeline_uuid' =>  $this->pipelineIdentifier,
            'state'         =>  ExecutionState::CREATED,
        ]);
    }

    /**
     * Create new pipeline through HTTP request
     * @return TestResponse
     */
    protected function createAndGetPipeline(): TestResponse
    {
        $response = $this->post('/task-manager/pipelines', [
            'name'              =>  'Example Pipeline',
            'description'       =>  'This is description for Example Pipeline',
        ]);

        $this->pipelineIdentifier = Arr::get($response->json('data'), 'uuid');

        return $response;
    }

    /**
     * Create new pipeline model through HTTP request
     * @return PipelineInterface
     */
    protected function createAndGetPipelineModel(): PipelineInterface
    {
        return new Pipeline($this->createAndGetPipeline()->json('data'));
    }

    /**
     * Create new pipeline task through HTTP request
     * @param bool $failPipelineCreation
     * @param bool $failTaskCreation
     * @return TestResponse
     */
    protected function createAndGetPipelineTask(bool $failPipelineCreation = false, bool $failTaskCreation = false): TestResponse
    {
        $pipeline = $this->getPipeline($this->createAndGetPipelineModel()->getUuid());
        $task = $this->getTask($this->createAndGetTaskModel()->getUuid());

        if ($failPipelineCreation) {
            $pipeline->setUuid(Str::uuid()->toString());
        }

        if ($failTaskCreation) {
            $task->setUuid(Str::uuid()->toString());
        }

        $response = $this->post('/task-manager/pipelines/' . $pipeline->getUuid() . '/tasks', [
            'task_uuid'         =>  $task->getUuid(),
            'order'             =>  1,
        ]);

        if ($response->isSuccessful()) {
            $createdTask = $this->getTask($task->getUuid());
            $createdPipeline = $this->getPipeline($pipeline->getUuid());
            $createdPipelineTask = $this->getPipelineTask($pipeline, $task);

            $this->assertSame($createdPipeline->getUuid(), $createdPipelineTask->getPipelineUuid());
            $this->assertSame($createdTask->getUuid(), $createdPipelineTask->getTaskUuid());
        }

        return $response;
    }

    /**
     * Create new pipeline task through HTTP request
     * @param bool $failPipelineCreation
     * @param bool $failTaskCreation
     * @return TestResponse
     */
    protected function createAndGetPipelineTaskWithIDs(bool $failPipelineCreation = false, bool $failTaskCreation = false): TestResponse
    {
        $pipeline = $this->getPipeline($this->createAndGetPipelineModel()->getUuid());
        $task = $this->getTask($this->createAndGetTaskModel()->getUuid());

        if ($failPipelineCreation) {
            $pipeline->setID(2);
        }

        if ($failTaskCreation) {
            $task->setID(2);
        }

        $response = $this->post('/task-manager/pipelines/' . $pipeline->getID() . '/tasks', [
            'task_uuid'         =>  $task->getID(),
            'order'             =>  1,
        ]);

        if ($response->isSuccessful()) {
            $createdTask = $this->getTask($task->getUuid());
            $createdPipeline = $this->getPipeline($pipeline->getUuid());
            $createdPipelineTask = $this->getPipelineTask($pipeline, $task);

            $this->assertSame($createdPipeline->getUuid(), $createdPipelineTask->getPipelineUuid());
            $this->assertSame($createdTask->getUuid(), $createdPipelineTask->getTaskUuid());
        }

        return $response;
    }

    /**
     * Get list of tasks associated with pipeline
     * @param PipelineInterface|array|null $pipeline
     * @return Collection
     */
    protected function getPipelineTasks(PipelineInterface|array|null $pipeline = null): Collection
    {
        if (!$pipeline) {
            return $this->createAndGetPipelineModel()->tasks;
        }
        if (is_array($pipeline)) {
            return (new Pipeline($pipeline))->tasks;
        }
        return $pipeline->tasks;
    }

    /**
     * Get first pipeline
     * @return PipelineInterface
     */
    protected function getFirstPipeline(): PipelineInterface
    {
        return Pipeline::all()->first();
    }

    /**
     * Get pipeline
     * @param string|int $identifier
     * @return PipelineInterface|null
     */
    protected function getPipeline(string|int $identifier): ?PipelineInterface
    {
        if (is_integer($identifier)) {
            return Pipeline::where('id', '=', $identifier)->first();
        }
        return Pipeline::where('uuid', '=', $identifier)->first();
    }

    /**
     * Get pipeline identifier as string
     * @param PipelineInterface|null $pipeline
     * @return string
     */
    protected function getPipelineStringIdentifier(?PipelineInterface $pipeline = null): string
    {
        if (is_null($pipeline)) {
            $pipeline = $this->getFirstPipeline();
        }
        return $pipeline->getUuid();
    }

    /**
     * Get pipeline identifier as string
     * @param PipelineInterface|null $pipeline
     * @return string
     */
    protected function getPipelineIntegerIdentifier(?PipelineInterface $pipeline = null): string
    {
        if (is_null($pipeline)) {
            $pipeline = $this->getFirstPipeline();
        }
        return $pipeline->getID();
    }

    /**
     * Get task action
     * @param PipelineInterface|null $pipeline
     * @param TaskInterface|null $task
     * @return PipelineTaskInterface|null
     */
    protected function getPipelineTask(?PipelineInterface $pipeline = null, ?TaskInterface $task = null): ?PipelineTaskInterface
    {
        return PipelineTask::where('pipeline_uuid', '=', $this->getPipelineStringIdentifier($pipeline))
            ->where('task_uuid', '=', $this->getTaskStringIdentifier($task))
            ->first();
    }

    /**
     * Get created pipeline task identifier
     * @return string
     */
    protected function getPipelineTaskIdentifier(): string
    {
        return $this->getPipelineTask()->getUuid();
    }

    /**
     * Assert that created pipeline task has same pipeline as specified
     * @param string $identifier
     * @return void
     */
    protected function assertPipelineTaskHasSamePipeline(string $identifier): void
    {
        $this->assertSame($identifier, $this->getPipelineTask()->getPipelineUuid());
    }

    /**
     * Assert that created pipeline task has same task as specified
     * @param string $identifier
     * @return void
     */
    protected function assertPipelineTaskHasSameTask(string $identifier): void
    {
        $this->assertSame($identifier, $this->getPipelineTask()->getTaskUuid());
    }

    /**
     * Assert that created pipeline task has same order as specified
     * @param int $order
     * @return void
     */
    protected function assertPipelineTaskHasSameOrder(int $order): void
    {
        $this->assertSame($order, $this->getPipelineTask()->getOrder());
    }

    /**
     * Validate array of actions
     * @param array $actions
     * @param array $except
     * @param array $only
     * @return void
     */
    protected function validateActionsArray(array $actions, array $except = [], array $only = []): void
    {
        foreach ($actions as $action) {
            $this->validateAction(
                action: $action,
                except: $except,
                only: $only,
            );
        }
    }

    /**
     * Validate single action
     * @param array $action
     * @param array $expectedNew
     * @param array $except
     * @param array $only
     * @return void
     */
    protected function validateAction(array $action, array $expectedNew = [], array $except = [], array $only = []): void
    {
        if (count($only) > 0) {
            $action = Arr::only($action, $only);
        } elseif (count($except) > 0) {
            $action = Arr::except($action, $except);
        }

        $actionKeys = array_keys($action);

        foreach ($actionKeys as $actionKey) {
            switch ($actionKey) {
                case 'id':
                    $this->assertArrayHasKey('id', $action, 'Expected array to have `id` key, but it is absent from array');
                    break;
                case 'uuid':
                    $this->assertArrayHasKey('uuid', $action, 'Expected array to have `id` key, but it is absent from array');
                    break;
                case 'name':
                    $this->assertSameExpected($action, 'name', 'Example Action', $expectedNew);
                    break;
                case 'description':
                    $this->assertSameExpected($action, 'description', 'This is description for Example Action', $expectedNew);
                    break;
                case 'type':
                    $this->assertSameExpected($action, 'type', ActionType::REMOTE, $expectedNew);
                    break;
                case 'command':
                    $this->assertSameExpected($action, 'command', 'lsb_release', $expectedNew);
                    break;
                case 'arguments':
                    $this->assertSameExpected($action, 'arguments', ['-a'], $expectedNew);
                    break;
                case 'working_dir':
                    $this->assertSameExpected($action, 'working_dir', null, $expectedNew);
                    break;
                case 'run_as':
                    $this->assertSameExpected($action, 'run_as', null, $expectedNew);
                    break;
                case 'use_sudo':
                    $this->assertSameExpected($action, 'use_sudo', false, $expectedNew);
                    break;
                case 'fail_on_error':
                    $this->assertSameExpected($action, 'fail_on_error', true, $expectedNew);
                    break;
                case 'created_at':
                    $this->assertNotNull(Arr::get($action, 'created_at'), 'Expected field `created_at` to be populated but it is empty');
                    break;
                case 'updated_at':
                    $this->assertNotNull(Arr::get($action, 'updated_at'), 'Expected field `updated_at` to be populated but it is empty');
                    break;
                case 'deleted_at':
                    $this->assertNull(Arr::get($action, 'deleted_at'), 'Expected field `deleted_at` to be empty but it is populated');
                    break;
            }
        }
    }

    /**
     * Validate array of tasks
     * @param array $tasks
     * @param array $except
     * @param array $only
     * @return void
     */
    protected function validateTasksArray(array $tasks, array $except = [], array $only = []): void
    {
        foreach ($tasks as $task) {
            $this->validateTask(
                task: $task,
                except: $except,
                only: $only,
            );
        }
    }

    /**
     * Validate single task
     * @param array $task
     * @param array $expectedNew
     * @param array $except
     * @param array $only
     * @return void
     */
    protected function validateTask(array $task, array $expectedNew = [], array $except = [], array $only = []): void
    {
        if (count($only) > 0) {
            $task = Arr::only($task, $only);
        } elseif (count($except) > 0) {
            $task = Arr::except($task, $except);
        }

        $taskKeys = array_keys($task);

        foreach ($taskKeys as $taskKey) {
            switch ($taskKey) {
                case 'id':
                    $this->assertArrayHasKey('id', $task, 'Expected array to have `id` key, but it is absent from array');
                    break;
                case 'uuid':
                    $this->assertArrayHasKey('uuid', $task, 'Expected array to have `id` key, but it is absent from array');
                    break;
                case 'name':
                    $this->assertSameExpected($task, 'name', 'Example Task', $expectedNew);
                    break;
                case 'description':
                    $this->assertSameExpected($task, 'description', 'This is description for Example Task', $expectedNew);
                    break;
                case 'type':
                    $this->assertSameExpected($task, 'type', TaskType::REMOTE, $expectedNew);
                    break;
                case 'fail_on_error':
                    $this->assertSameExpected($task, 'fail_on_error', false, $expectedNew);
                    break;
                case 'created_at':
                    $this->assertNotNull(Arr::get($task, 'created_at'), 'Expected field `created_at` to be populated but it is empty');
                    break;
                case 'updated_at':
                    $this->assertNotNull(Arr::get($task, 'updated_at'), 'Expected field `updated_at` to be populated but it is empty');
                    break;
                case 'deleted_at':
                    $this->assertNull(Arr::get($task, 'deleted_at'), 'Expected field `deleted_at` to be empty but it is populated');
                    break;
            }
        }
    }

    /**
     * Validate array of task actions
     * @param array $taskActions
     * @param array $except
     * @param array $only
     * @return void
     */
    protected function validateTaskActionsArray(array $taskActions, array $except = [], array $only = []): void
    {
        foreach ($taskActions as $taskAction) {
            $this->validateTaskAction(
                taskAction: $taskAction,
                except: $except,
                only: $only,
            );
        }
    }

    /**
     * Validate single task action
     * @param array $taskAction
     * @param array $expectedNew
     * @param array $except
     * @param array $only
     * @return void
     */
    protected function validateTaskAction(array $taskAction, array $expectedNew = [], array $except = [], array $only = []): void
    {
        if (count($only) > 0) {
            $taskAction = Arr::only($taskAction, $only);
        } elseif (count($except) > 0) {
            $taskAction = Arr::except($taskAction, $except);
        }

        $taskActionKeys = array_keys($taskAction);

        foreach ($taskActionKeys as $taskActionKey) {
            switch ($taskActionKey) {
                case 'uuid':
                    $this->assertArrayHasKey('uuid', $taskAction, 'Expected array to have `id` key, but it is absent from array');
                    break;
                case 'task_uuid':
                    $this->assertSameExpected($taskAction, 'task_uuid', $this->getTaskStringIdentifier(), $expectedNew);
                    break;
                case 'action_uuid':
                    $this->assertSameExpected($taskAction, 'action_uuid', $this->getActionStringIdentifier(), $expectedNew);
                    break;
                case 'order':
                    $this->assertSameExpected($taskAction, 'order', 1, $expectedNew);
                    break;
                case 'created_at':
                    $this->assertNotNull(Arr::get($taskAction, 'created_at'), 'Expected field `created_at` to be populated but it is empty');
                    break;
                case 'updated_at':
                    $this->assertNotNull(Arr::get($taskAction, 'updated_at'), 'Expected field `updated_at` to be populated but it is empty');
                    break;
                case 'deleted_at':
                    $this->assertNull(Arr::get($taskAction, 'deleted_at'), 'Expected field `deleted_at` to be empty but it is populated');
                    break;
            }
        }
    }

    /**
     * Validate array of pipelines
     * @param array $pipelines
     * @param array $except
     * @param array $only
     * @return void
     */
    protected function validatePipelinesArray(array $pipelines, array $except = [], array $only = []): void
    {
        foreach ($pipelines as $pipeline) {
            $this->validatePipeline(
                pipeline: $pipeline,
                except: $except,
                only: $only,
            );
        }
    }

    /**
     * Validate single pipeline
     * @param array $pipeline
     * @param array $expectedNew
     * @param array $except
     * @param array $only
     * @return void
     */
    protected function validatePipeline(array $pipeline, array $expectedNew = [], array $except = [], array $only = []): void
    {
        if (count($only) > 0) {
            $pipeline = Arr::only($pipeline, $only);
        } elseif (count($except) > 0) {
            $pipeline = Arr::except($pipeline, $except);
        }

        $pipelineKeys = array_keys($pipeline);

        foreach ($pipelineKeys as $pipelineKey) {
            switch ($pipelineKey) {
                case 'id':
                    $this->assertArrayHasKey('id', $pipeline, 'Expected array to have `id` key, but it is absent from array');
                    break;
                case 'uuid':
                    $this->assertArrayHasKey('uuid', $pipeline, 'Expected array to have `id` key, but it is absent from array');
                    break;
                case 'name':
                    $this->assertSameExpected($pipeline, 'name', 'Example Pipeline', $expectedNew);
                    break;
                case 'description':
                    $this->assertSameExpected($pipeline, 'description', 'This is description for Example Pipeline', $expectedNew);
                    break;
            }
        }
    }

    /**
     * Validate array of pipeline tasks
     * @param array $pipelineTasks
     * @param array $except
     * @param array $only
     * @return void
     */
    protected function validatePipelineTasksArray(array $pipelineTasks, array $except = [], array $only = []): void
    {
        foreach ($pipelineTasks as $pipelineTask) {
            $this->validatePipelineTask(
                pipelineTask: $pipelineTask,
                except: $except,
                only: $only,
            );
        }
    }

    /**
     * Validate single pipeline task
     * @param array $pipelineTask
     * @param array $expectedNew
     * @param array $except
     * @param array $only
     * @return void
     */
    protected function validatePipelineTask(array $pipelineTask, array $expectedNew = [], array $except = [], array $only = []): void
    {
        if (count($only) > 0) {
            $pipelineTask = Arr::only($pipelineTask, $only);
        } elseif (count($except) > 0) {
            $pipelineTask = Arr::except($pipelineTask, $except);
        }

        $pipelineTaskKeys = array_keys($pipelineTask);

        foreach ($pipelineTaskKeys as $pipelineTaskKey) {
            switch ($pipelineTaskKey) {
                case 'uuid':
                    $this->assertArrayHasKey('uuid', $pipelineTask, 'Expected array to have `id` key, but it is absent from array');
                    break;
                case 'pipeline_uuid':
                    $this->assertSameExpected($pipelineTask, 'pipeline_uuid', $this->getPipelineStringIdentifier(), $expectedNew);
                    break;
                case 'task_uuid':
                    $this->assertSameExpected($pipelineTask, 'task_uuid', $this->getTaskStringIdentifier(), $expectedNew);
                    break;
                case 'order':
                    $this->assertSameExpected($pipelineTask, 'order', 1, $expectedNew);
                    break;
                case 'created_at':
                    $this->assertNotNull(Arr::get($pipelineTask, 'created_at'), 'Expected field `created_at` to be populated but it is empty');
                    break;
                case 'updated_at':
                    $this->assertNotNull(Arr::get($pipelineTask, 'updated_at'), 'Expected field `updated_at` to be populated but it is empty');
                    break;
                case 'deleted_at':
                    $this->assertNull(Arr::get($pipelineTask, 'deleted_at'), 'Expected field `deleted_at` to be empty but it is populated');
                    break;
            }
        }
    }

    /**
     * Assert that value is the same or one from `expected` array
     * @param array $array
     * @param string $field
     * @param mixed $default
     * @param array $expectedNew
     * @return void
     */
    protected function assertSameExpected(array $array, string $field, mixed $default, array $expectedNew = []): void
    {
        $expected = Arr::get($expectedNew, $field, $default);
        $received = Arr::get($array, $field);

        $this->assertSame(
            Arr::get($expectedNew, $field, $default),
            $received,
            sprintf(
                'Expected `%s` to be equal, but it is not | Expected: %s | Received: %s',
                $field,
                $this->prepareValueForSprintf($expected),
                $this->prepareValueForSprintf($received)
            )
        );
    }

    /**
     * Prepare value for sprintf
     * @param mixed $value
     * @return string
     */
    private function prepareValueForSprintf(mixed $value): string
    {
        if (is_array($value)) {
            return json_encode($value);
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        return (string) $value;
    }
}
