<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Entities;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use ConsulConfigManager\Tasks\Models\Task;
use ConsulConfigManager\Tasks\Models\Action;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\Models\Pipeline;
use ConsulConfigManager\Tasks\Enums\ActionType;
use ConsulConfigManager\Tasks\Models\ActionHost;
use ConsulConfigManager\Tasks\Models\TaskAction;
use ConsulConfigManager\Tasks\Models\PipelineTask;
use ConsulConfigManager\Tasks\Enums\ExecutionState;
use ConsulConfigManager\Tasks\Models\TaskExecution;
use ConsulConfigManager\Consul\Agent\Models\Service;
use ConsulConfigManager\Tasks\Models\ActionExecution;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Models\PipelineExecution;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionHostInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskActionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionInterface;
use ConsulConfigManager\Consul\Agent\Interfaces\ServiceInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionInterface;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\TaskEntity;
use ConsulConfigManager\Tasks\Services\TaskRunner\Actions\RemoteAction;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\ActionEntity;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\ServerEntity;
use ConsulConfigManager\Tasks\Services\TaskRunner\Actions\AbstractAction;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\PipelineEntity;

/**
 * Class AbstractEntityTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Entities
 */
abstract class AbstractEntityTest extends TestCase
{
    /**
     * Server identifier for execution
     * @var string
     */
    protected string $serverIdentifier = 'a2ec83ed-2a05-437a-b438-58559eaa7ffe';

    /**
     * Action identifier for execution
     * @var string
     */
    protected string $actionIdentifier = 'ba6addfc-c524-415e-8f68-b60dd1146840';

    /**
     * Task identifier for execution
     * @var string
     */
    protected string $taskIdentifier = '55dfebdd-a386-482c-a16b-cd2afa4e50cb';

    /**
     * Task Action identifier
     * @var string
     */
    protected string $taskActionIdentifier = 'ced2597a-ccac-4f30-bdba-04cea2b3c697';

    /**
     * Pipeline identifier for execution
     * @var string
     */
    protected string $pipelineIdentifier = 'ec019e70-ec3d-4ecd-b744-7870b4fbc7a6';

    /**
     * Pipeline Task identifier
     * @var string
     */
    protected string $pipelineTaskIdentifier = '79a22700-a13b-4e3c-9537-b2ad87064e65';

    /**
     * Execution identifier for execution
     * @var string
     */
    protected string $executionIdentifier = 'c4f935d8-f539-4207-9824-1e6e2da6a211';

    /**
     * Create and get remote task
     * @return RemoteAction
     */
    protected function createAndGetRemoteAction(): RemoteAction
    {
        $data = $this->runnerDataProvider();
        $instance = new RemoteAction(
            Arr::get($data, 'host'),
            Arr::get($data, 'port'),
        );
        $instance
            ->setExecutionIdentifier(Arr::get($data, 'execution_id'))
            ->setPipelineIdentifier(Arr::get($data, 'pipeline_id'))
            ->setTaskIdentifier(Arr::get($data, 'task_id'))
            ->setActionIdentifier(Arr::get($data, 'action_id'))
            ->setServerIdentifier(Arr::get($data, 'server_id'))
            ->setCommand(Arr::get($data, 'command'))
            ->setArguments(Arr::get($data, 'arguments'))
            ->setWorkingDirectory(Arr::get($data, 'working_dir'))
            ->setRunAs(Arr::get($data, 'run_as'))
            ->setUseSudo(Arr::get($data, 'use_sudo'))
            ->setFailOnError(Arr::get($data, 'fail_on_error'));
        return $instance;
    }

    /**
     * Create and get new server
     * @return ServiceInterface
     */
    protected function createAndGetServer(): ServiceInterface
    {
        return Service::create([
            'uuid'          =>  $this->serverIdentifier,
            'identifier'    =>  'ccm-example.local-127.0.0.1',
            'service'       =>  'ccm',
            'address'       =>  '127.0.0.1',
            'port'          =>  32175,
            'datacenter'    =>  'dc1',
            'tags'          =>  [],
            'meta'          =>  [],
            'online'        =>  1,
            'environment'   =>  'testing',
        ]);
    }

    /**
     * Create and get new action
     * @return ActionInterface
     */
    protected function createAndGetAction(): ActionInterface
    {
        return Action::create([
            'uuid'                  =>  $this->actionIdentifier,
            'name'                  =>  'Example Action',
            'description'           =>  'Example Action Description',
            'type'                  =>  ActionType::REMOTE,
            'command'               =>  'php',
            'arguments'             =>  [
                'test.php',
            ],
            'working_dir'           =>  '/home/scripts',
            'run_as'                =>  null,
            'use_sudo'              =>  false,
            'fail_on_error'         =>  true,
        ]);
    }

    /**
     * Attach server to action
     * @return ActionHostInterface
     */
    protected function attachServerToAction(): ActionHostInterface
    {
        return ActionHost::create([
            'action_uuid'       =>  $this->actionIdentifier,
            'service_uuid'      =>  $this->serverIdentifier,
        ]);
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
     * Create and get new task
     * @param bool $failOnError
     * @return TaskInterface
     */
    protected function createAndGetTask(bool $failOnError = false): TaskInterface
    {
        return Task::create([
            'uuid'          =>  $this->taskIdentifier,
            'name'          =>  'Example Task',
            'description'   =>  'Description for Example Task',
            'fail_on_error' =>  $failOnError,
        ]);
    }

    /**
     * Create and get new task execution
     * @param int $state
     * @return TaskExecutionInterface
     */
    protected function createAndGetTaskExecution(int $state = ExecutionState::CREATED): TaskExecutionInterface
    {
        return TaskExecution::create([
            'task_uuid'                 =>  $this->taskIdentifier,
            'pipeline_uuid'             =>  $this->pipelineIdentifier,
            'pipeline_execution_uuid'   =>  $this->executionIdentifier,
            'state'                     =>  $state,
        ]);
    }

    /**
     * Attach action to task
     * @return TaskActionInterface
     */
    protected function attachActionToTask(): TaskActionInterface
    {
        return TaskAction::create([
            'uuid'          =>  $this->taskActionIdentifier,
            'task_uuid'     =>  $this->taskIdentifier,
            'action_uuid'   =>  $this->actionIdentifier,
            'order'         =>  1,
        ]);
    }

    /**
     * Create and get new pipeline
     * @return PipelineInterface
     */
    protected function createAndGetPipeline(): PipelineInterface
    {
        return Pipeline::create([
            'uuid'          =>  $this->pipelineIdentifier,
            'name'          =>  'Example Pipeline',
            'description'   =>  'Description for Example Pipeline',
        ]);
    }

    /**
     * Create and get new pipeline execution
     * @param int $state
     * @return PipelineExecutionInterface
     */
    protected function createAndGetPipelineExecution(int $state = ExecutionState::CREATED): PipelineExecutionInterface
    {
        return PipelineExecution::create([
            'uuid'          =>  $this->executionIdentifier,
            'pipeline_uuid' =>  $this->pipelineIdentifier,
            'state'         =>  $state,
        ]);
    }

    /**
     * Attach task to pipeline
     * @return PipelineTaskInterface
     */
    protected function attachTaskToPipeline(): PipelineTaskInterface
    {
        return PipelineTask::create([
            'uuid'          =>  $this->pipelineTaskIdentifier,
            'pipeline_uuid' =>  $this->pipelineIdentifier,
            'task_uuid'     =>  $this->taskIdentifier,
            'order'         =>  1,
        ]);
    }

    /**
     * Get created server instance
     * @return ServiceInterface|null
     */
    protected function getServerInstance(): ?ServiceInterface
    {
        return Service::where('uuid', '=', $this->serverIdentifier)
            ->orWhere('id', '=', 1)
            ->first();
    }

    /**
     * Get created action instance
     * @return ActionInterface|null
     */
    protected function getActionInstance(): ?ActionInterface
    {
        return Action::where('uuid', '=', $this->actionIdentifier)
            ->orWhere('id', '=', 1)
            ->first();
    }

    /**
     * Get created action execution instance
     * @return ActionExecutionInterface|null
     */
    protected function getActionExecutionInstance(): ?ActionExecutionInterface
    {
        return ActionExecution::where('action_uuid', '=', $this->actionIdentifier)
            ->orWhere('id', '=', 1)
            ->first();
    }

    /**
     * Get created task instance
     * @return TaskInterface|null
     */
    protected function getTaskInstance(): ?TaskInterface
    {
        return Task::where('uuid', '=', $this->taskIdentifier)
            ->orWhere('id', '=', 1)
            ->first();
    }

    /**
     * Get created task execution instance
     * @return TaskExecutionInterface|null
     */
    protected function getTaskExecutionInstance(): ?TaskExecutionInterface
    {
        return TaskExecution::where('task_uuid', '=', $this->taskIdentifier)
            ->orWhere('id', '=', 1)
            ->first();
    }

    /**
     * Get created pipeline instance
     * @return PipelineInterface|null
     */
    protected function getPipelineInstance(): ?PipelineInterface
    {
        return Pipeline::where('uuid', '=', $this->pipelineIdentifier)
            ->orWhere('id', '=', 1)
            ->first();
    }

    /**
     * Get created pipeline execution instance
     * @return PipelineExecutionInterface|null
     */
    protected function getPipelineExecutionInstance(): ?PipelineExecutionInterface
    {
        return PipelineExecution::where('uuid', '=', $this->executionIdentifier)
            ->orWhere('id', '=', 1)
            ->first();
    }

    /**
     * Assert that provided runner is the same
     * @param AbstractAction $new
     * @return void
     */
    protected function assertSameRunner(AbstractAction $new): void
    {
        $old = $this->createAndGetRemoteAction();
        $this->assertSame($new->toActionArray(), $old->toActionArray());
    }

    /**
     * Assert that provided server matches existing server
     * @param ServiceInterface $new
     * @return void
     */
    protected function assertSameServer(ServiceInterface $new): void
    {
        $old = $this->getServerInstance();

        $this->assertSame($old->getId(), $new->getId());
        $this->assertSame($old->getUuid(), $new->getUuid());
        $this->assertSame($old->getIdentifier(), $new->getIdentifier());
        $this->assertSame($old->getService(), $new->getService());
        $this->assertSame($old->getAddress(), $new->getAddress());
        $this->assertSame($old->getPort(), $new->getPort());
        $this->assertSame($old->getDatacenter(), $new->getDatacenter());
        $this->assertSame($old->getTags(), $new->getTags());
        $this->assertSame($old->getMeta(), $new->getMeta());
        $this->assertSame($old->isOnline(), $new->isOnline());
        $this->assertSame($old->getEnvironment(), $new->getEnvironment());
    }

    /**
     * Assert that provided action matches existing action
     * @param ActionInterface $new
     * @return void
     */
    protected function assertSameAction(ActionInterface $new): void
    {
        $old = $this->getActionInstance();

        $this->assertSame($old->getID(), $new->getID());
        $this->assertSame($old->getUuid(), $new->getUuid());
        $this->assertSame($old->getName(), $new->getName());
        $this->assertSame($old->getDescription(), $new->getDescription());
        $this->assertSame($old->getType(), $new->getType());
        $this->assertSame($old->getCommand(), $new->getCommand());
        $this->assertSame($old->getArguments(), $new->getArguments());
        $this->assertSame($old->getWorkingDirectory(), $new->getWorkingDirectory());
        $this->assertSame($old->getRunAs(), $new->getRunAs());
        $this->assertSame($old->isUsingSudo(), $new->isUsingSudo());
        $this->assertSame($old->isFailingOnError(), $new->isFailingOnError());
    }

    /**
     * Assert that provided action execution matches existing action execution
     * @param ActionExecutionInterface $new
     * @return void
     */
    protected function assertSameActionExecution(ActionExecutionInterface $new): void
    {
        $old = $this->getActionExecutionInstance();

        $this->assertSame($old->getID(), $new->getID());
        $this->assertSame($old->getServerUuid(), $new->getServerUuid());
        $this->assertSame($old->getActionUuid(), $new->getActionUuid());
        $this->assertSame($old->getTaskUuid(), $new->getTaskUuid());
        $this->assertSame($old->getPipelineUuid(), $new->getPipelineUuid());
        $this->assertSame($old->getPipelineExecutionUuid(), $new->getPipelineExecutionUuid());
        $this->assertSame($old->getState(), $new->getState());
    }

    /**
     * Assert that provided task matches existing task
     * @param TaskInterface $new
     * @return void
     */
    protected function assertSameTask(TaskInterface $new): void
    {
        $old = $this->getTaskInstance();

        $this->assertSame($old->getID(), $new->getID());
        $this->assertSame($old->getUuid(), $new->getUuid());
        $this->assertSame($old->getName(), $new->getName());
        $this->assertSame($old->getDescription(), $new->getDescription());
        $this->assertSame($old->isFailingOnError(), $new->isFailingOnError());
    }

    /**
     * Assert that provided task execution matches existing task execution
     * @param TaskExecutionInterface $new
     * @return void
     */
    protected function assertSameTaskExecution(TaskExecutionInterface $new): void
    {
        $old = $this->getTaskExecutionInstance();

        $this->assertSame($old->getID(), $new->getID());
        $this->assertSame($old->getTaskUuid(), $new->getTaskUuid());
        $this->assertSame($old->getPipelineUuid(), $new->getPipelineUuid());
        $this->assertSame($old->getPipelineExecutionUuid(), $new->getPipelineExecutionUuid());
        $this->assertSame($old->getState(), $new->getState());
    }

    /**
     * Assert that provided pipeline matches existing pipeline
     * @param PipelineInterface $new
     * @return void
     */
    protected function assertSamePipeline(PipelineInterface $new): void
    {
        $old = $this->getPipelineInstance();

        $this->assertSame($old->getID(), $new->getID());
        $this->assertSame($old->getUuid(), $new->getUuid());
        $this->assertSame($old->getName(), $new->getName());
        $this->assertSame($old->getDescription(), $new->getDescription());
    }

    /**
     * Assert that provided pipeline execution matches existing pipeline execution
     * @param PipelineExecutionInterface $new
     * @return void
     */
    protected function assertSamePipelineExecution(PipelineExecutionInterface $new): void
    {
        $old = $this->getPipelineExecutionInstance();

        $this->assertSame($old->getID(), $new->getID());
        $this->assertSame($old->getUuid(), $new->getUuid());
        $this->assertSame($old->getPipelineUuid(), $new->getPipelineUuid());
        $this->assertSame($old->getState(), $new->getState());
    }

    /**
     * Generate array from database data that should represent ActionEntity toArray result
     * @return array
     */
    protected function actionEntityToArrayFromDatabase(): array
    {
        $execution = $this->getActionExecutionInstance();
        $action = $this->getActionInstance();
        $runner = $this->createAndGetRemoteAction();

        return [
            'execution'                     =>  [
                'server_uuid'               =>  $execution->getServerUuid(),
                'action_uuid'               =>  $execution->getActionUuid(),
                'task_uuid'                 =>  $execution->getTaskUuid(),
                'pipeline_uuid'             =>  $execution->getPipelineUuid(),
                'pipeline_execution_uuid'   =>  $execution->getPipelineExecutionUuid(),
                'state'                     =>  $execution->getState(),
                'updated_at'                =>  $this->carbonToString($execution->created_at),
                'created_at'                =>  $this->carbonToString($execution->updated_at),
                'id'                        =>  $execution->getID(),
            ],
            'action'                        =>  [
                'id'                        =>  $action->getID(),
                'uuid'                      =>  $action->getUuid(),
                'name'                      =>  $action->getName(),
                'description'               =>  $action->getDescription(),
                'type'                      =>  $action->getType(),
                'command'                   =>  $action->getCommand(),
                'arguments'                 =>  $action->getArguments(),
                'working_dir'               =>  $action->getWorkingDirectory(),
                'run_as'                    =>  $action->getRunAs(),
                'use_sudo'                  =>  $action->isUsingSudo(),
                'fail_on_error'             =>  $action->isFailingOnError(),
                'deleted_at'                =>  null,
                'created_at'                =>  $this->carbonToString($action->created_at),
                'updated_at'                =>  $this->carbonToString($action->updated_at),
            ],
            'runner'                        =>  [
                'execution_id'              =>  $runner->getExecutionIdentifier(),
                'pipeline_id'               =>  $runner->getPipelineIdentifier(),
                'task_id'                   =>  $runner->getTaskIdentifier(),
                'action_id'                 =>  $runner->getActionIdentifier(),
                'server_id'                 =>  $runner->getServerIdentifier(),
                'command'                   =>  $runner->getCommand(),
                'arguments'                 =>  $runner->getArguments(),
                'working_dir'               =>  $runner->getWorkingDirectory(),
                'run_as'                    =>  $runner->getRunAs(),
                'use_sudo'                  =>  $runner->getUseSudo(),
                'fail_on_error'             =>  $runner->getFailOnError(),
                'type'                      =>  ActionType::REMOTE,
            ],
        ];
    }

    /**
     * Generate array from database data that should represent ServerEntity toArray result
     * @return array
     */
    protected function serverEntityToArrayFromDatabase(): array
    {
        $server = $this->getServerInstance();
        return [
            'identifier'        =>  $server->getUuid(),
            'server'            =>  [
                'id'            =>  $server->getId(),
                'uuid'          =>  $server->getUuid(),
                'identifier'    =>  $server->getIdentifier(),
                'service'       =>  $server->getService(),
                'address'       =>  $server->getAddress(),
                'port'          =>  $server->getPort(),
                'datacenter'    =>  $server->getDatacenter(),
                'tags'          =>  $server->getTags(),
                'meta'          =>  $server->getMeta(),
                'online'        =>  $server->isOnline(),
                'environment'   =>  $server->getEnvironment(),
                'deleted_at'    =>  null,
                'created_at'    =>  $this->carbonToString($server->created_at),
                'updated_at'    =>  $this->carbonToString($server->updated_at),
            ],
            'actions'           =>  [
                $this->actionEntityToArrayFromDatabase(),
            ],
        ];
    }

    /**
     * Generate array from database data that should represent TaskEntity toArray result
     * @return array
     */
    protected function taskEntityToArrayFromDatabase(): array
    {
        $execution = $this->getTaskExecutionInstance();
        $task = $this->getTaskInstance();
        return [
            'execution'                     =>  [
                'task_uuid'                 =>  $execution->getTaskUuid(),
                'pipeline_uuid'             =>  $execution->getPipelineUuid(),
                'pipeline_execution_uuid'   =>  $execution->getPipelineExecutionUuid(),
                'state'                     =>  $execution->getState(),
                'updated_at'                =>  $this->carbonToString($execution->updated_at),
                'created_at'                =>  $this->carbonToString($execution->created_at),
                'id'                        =>  $execution->getID(),
            ],
            'task'                          =>  [
                'id'                        =>  $task->getID(),
                'uuid'                      =>  $task->getUuid(),
                'name'                      =>  $task->getName(),
                'description'               =>  $task->getDescription(),
                'fail_on_error'             =>  $task->isFailingOnError(),
                'deleted_at'                =>  null,
                'created_at'                =>  $this->carbonToString($task->created_at),
                'updated_at'                =>  $this->carbonToString($task->updated_at),
            ],
            'servers'                       =>  [
                $this->serverEntityToArrayFromDatabase(),
            ],
        ];
    }

    /**
     * Generate array from database data that should represent PipelineEntity toArray result
     * @return array
     */
    protected function pipelineEntityToArrayFromDatabase(): array
    {
        $execution = $this->getPipelineExecutionInstance();
        $pipeline = $this->getPipelineInstance();

        return [
            'execution'                     =>  [
                'uuid'                      =>  $execution->getUuid(),
                'pipeline_uuid'             =>  $execution->getPipelineUuid(),
                'state'                     =>  $execution->getState(),
                'updated_at'                =>  $this->carbonToString($execution->updated_at),
                'created_at'                =>  $this->carbonToString($execution->created_at),
                'id'                        =>  $execution->getID(),
            ],
            'pipeline'                      =>  [
                'id'                        =>  $pipeline->getID(),
                'uuid'                      =>  $pipeline->getUuid(),
                'name'                      =>  $pipeline->getName(),
                'description'               =>  $pipeline->getDescription(),
                'deleted_at'                =>  null,
                'created_at'                =>  $this->carbonToString($pipeline->created_at),
                'updated_at'                =>  $this->carbonToString($pipeline->updated_at),
            ],
            'tasks'                         =>  [
                $this->taskEntityToArrayFromDatabase(),
            ],
        ];
    }

    /**
     * Assert that toArray method of ActionEntity returns valid data
     * @param ActionEntity $instance
     * @return void
     */
    protected function assertSameActionEntityToArray(ActionEntity $instance): void
    {
        $this->assertSame(
            $this->actionEntityToArrayFromDatabase(),
            $instance->toArray()
        );
    }

    /**
     * Assert that toArray method of ServerEntity returns valid data
     * @param ServerEntity $instance
     * @return void
     */
    protected function assertSameServerEntityToArray(ServerEntity $instance): void
    {
        $this->assertSame(
            $this->serverEntityToArrayFromDatabase(),
            $instance->toArray()
        );
    }

    /**
     * Assert that toArray method of TaskEntity returns valid data
     * @param TaskEntity $instance
     * @return void
     */
    protected function assertSameTaskEntityToArray(TaskEntity $instance): void
    {
        $this->assertSame(
            $this->taskEntityToArrayFromDatabase(),
            $instance->toArray()
        );
    }

    /**
     * Assert that toArray method of PipelineEntity returns valid data
     * @param PipelineEntity $instance
     * @return void
     */
    protected function assertSamePipelineEntityToArray(PipelineEntity $instance): void
    {
        $this->assertSame(
            $this->pipelineEntityToArrayFromDatabase(),
            $instance->toArray()
        );
    }

    /**
     * Convert carbon instance to valid string
     * @param Carbon $carbon
     * @return string
     */
    protected function carbonToString(Carbon $carbon): string
    {
        return $carbon->toDateTimeLocalString('microsecond') . 'Z';
    }

    /**
     * Data provider
     * @return array
     */
    public function runnerDataProvider(): array
    {
        return [
            'host'                      =>  '127.0.0.1',
            'port'                      =>  32175,
            'execution_id'              =>  $this->executionIdentifier,
            'pipeline_id'               =>  $this->pipelineIdentifier,
            'task_id'                   =>  $this->taskIdentifier,
            'action_id'                 =>  $this->actionIdentifier,
            'server_id'                 =>  $this->serverIdentifier,
            'working_dir'               =>  '/home/scripts',
            'command'                   =>  'php',
            'arguments'                 =>  [
                'test.php',
            ],
            'run_as'                    =>  'example',
            'use_sudo'                  =>  false,
            'fail_on_error'             =>  true,
        ];
    }
}
