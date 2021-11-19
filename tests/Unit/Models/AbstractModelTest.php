<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Models;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Models\Task;
use ConsulConfigManager\Tasks\Models\Action;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\Models\Pipeline;
use ConsulConfigManager\Tasks\Models\ActionHost;
use ConsulConfigManager\Tasks\Models\TaskAction;
use ConsulConfigManager\Tasks\Models\PipelineTask;
use ConsulConfigManager\Consul\Agent\Models\Service;
use ConsulConfigManager\Tasks\Models\ActionExecution;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Models\PipelineExecution;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionHostInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskActionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;

/**
 * Class AbstractModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Models
 */
abstract class AbstractModelTest extends TestCase
{
    /**
     * Abstract Model Test Static: Service UUID
     * UUID used to create models suitable for relations querying
     * @var string
     */
    protected static string $serviceUUID = '40750e90-4be1-4cae-8b51-2c00fb0f0569';

    /**
     * Abstract Model Test Static: Action UUID
     * UUID used to create models suitable for relations querying
     * @var string
     */
    protected static string $actionUUID = '3a1f6b85-8c32-45eb-835c-01c28fc035fe';

    /**
     * Abstract Model Test Static: Task UUID
     * UUID used to create models suitable for relations querying
     * @var string
     */
    protected static string $taskUUID = '100c2e3b-56b9-48c3-bd73-97c789bfec63';

    /**
     * Abstract Model Test Static: Pipeline UUID
     * UUID used to create models suitable for relations querying
     * @var string
     */
    protected static string $pipelineUUID = '277d227e-ac47-4b89-ad40-8c966e42ac7b';

    /**
     * Action execution model data provider
     * @return \array[][]
     */
    protected function actionExecutionModelDataProvider(): array
    {
        return [
            'example_action_execution_entry'    =>  [
                'data'                          =>  [
                    'id'                        =>  1,
                    'action_uuid'               =>  self::$actionUUID,
                    'task_uuid'                 =>  self::$taskUUID,
                    'pipeline_uuid'             =>  self::$pipelineUUID,
                    'state'                     =>  1,
                ],
            ],
        ];
    }

    /**
     * Action host model data provider
     * @see ActionHost
     * @return \string[][][]
     */
    protected function actionHostModelDataProvider(): array
    {
        return [
            'example_action_host_entity'        =>  [
                'data'                          =>  [
                    'action_uuid'               =>  self::$actionUUID,
                    'service_uuid'              =>  'cd847b07-5387-456d-9690-db4d182ea14a',
                ],
            ],
        ];
    }

    /**
     * Action model data provider
     * @see Action
     * @return array
     */
    protected function actionModelDataProvider(): array
    {
        return [
            'example_action_entity'     =>  [
                'data'                  =>  [
                    'id'                =>  1,
                    'uuid'              =>  self::$actionUUID,
                    'name'              =>  'Example Action',
                    'description'       =>  'Example Action Description',
                    'type'              =>  1,
                    'command'           =>  'php',
                    'arguments'         =>  ['test.php'],
                    'working_dir'       =>  '/home/cabinet',
                    'run_as'            =>  'cabinet',
                    'use_sudo'          =>  false,
                    'fail_on_error'     =>  true,
                ],
            ],
        ];
    }

    /**
     * Pipeline execution model data provider
     * @see PipelineExecution
     * @return \array[][]
     */
    protected function pipelineExecutionModelDataProvider(): array
    {
        return [
            'example_pipeline_execution_entity'     =>  [
                'data'                              =>  [

                ],
            ],
        ];
    }

    /**
     * Pipeline task model data provider
     * @see PipelineTask
     * @return \array[][]
     */
    protected function pipelineTaskModelDataProvider(): array
    {
        return [
            'example_pipeline_task_entity'  =>  [
                'data'                      =>  [
                    'pipeline_uuid'         =>  self::$pipelineUUID,
                    'task_uuid'             =>  self::$taskUUID,
                    'order'                 =>  1,
                ],
            ],
        ];
    }

    /**
     * Pipeline model data provider
     * @see Pipeline
     * @return \array[][]
     */
    protected function pipelineModelDataProvider(): array
    {
        return [
            'example_pipeline_entity'   =>  [
                'data'                  =>  [
                    'id'                =>  1,
                    'uuid'              =>  self::$pipelineUUID,
                    'name'              =>  'Example Pipeline',
                    'description'       =>  'Example Pipeline Description',
                ],
            ],
        ];
    }

    /**
     * Task model action data provider
     * @see TaskAction
     * @return \array[][]
     */
    protected function taskActionModelDataProvider(): array
    {
        return [
            'example_task_action_entity'    =>  [
                'data'                      =>  [
                    'task_uuid'             =>  self::$taskUUID,
                    'action_uuid'           =>  self::$actionUUID,
                    'order'                 =>  1,
                ],
            ],
        ];
    }

    /**
     * Task model data provider
     * @see Task
     * @return \array[][]
     */
    protected function taskModelDataProvider(): array
    {
        return [
            'example_task_entity'   =>  [
                'data'              =>  [
                    'id'            =>  1,
                    'uuid'          =>  self::$taskUUID,
                    'name'          =>  'Example Task',
                    'description'   =>  'Example Task Description',
                    'type'          =>  1,
                ],
            ],
        ];
    }

    /**
     * Create action execution model instance
     * @param array $data
     * @return ActionExecutionInterface
     */
    protected function actionExecutionModel(array $data): ActionExecutionInterface
    {
        return ActionExecution::factory()->make($data);
    }

    /**
     * Create action host model instance
     * @param array $data
     * @return ActionHostInterface
     */
    protected function actionHostModel(array $data): ActionHostInterface
    {
        return ActionHost::factory()->make($data);
    }

    /**
     * Create action model instance
     * @param array $data
     * @return ActionInterface
     */
    protected function actionModel(array $data): ActionInterface
    {
        return Action::factory()->make($data);
    }

    /**
     * Create pipeline execution model instance
     * @param array $data
     * @return PipelineExecutionInterface
     */
    protected function pipelineExecutionModel(array $data): PipelineExecutionInterface
    {
        return PipelineExecution::factory()->make($data);
    }

    /**
     * Create pipeline task model instance
     * @param array $data
     * @return PipelineTaskInterface
     */
    protected function pipelineTaskModel(array $data): PipelineTaskInterface
    {
        return PipelineTask::factory()->make($data);
    }

    /**
     * Create pipeline model instance
     * @param array $data
     * @return PipelineInterface
     */
    protected function pipelineModel(array $data): PipelineInterface
    {
        return Pipeline::factory()->make($data);
    }

    /**
     * Create task action model instance
     * @param array $data
     * @return TaskActionInterface
     */
    protected function taskActionModel(array $data): TaskActionInterface
    {
        return TaskAction::factory()->make($data);
    }

    /**
     * Create task model instance
     * @param array $data
     * @return TaskInterface
     */
    protected function taskModel(array $data): TaskInterface
    {
        return Task::factory()->make($data);
    }

    /**
     * Create instance of action repository
     * @return ActionRepositoryInterface
     */
    protected function actionRepository(): ActionRepositoryInterface
    {
        return $this->app->make(ActionRepositoryInterface::class);
    }

    /**
     * Create instance of pipeline repository
     * @return PipelineRepositoryInterface
     */
    protected function pipelineRepository(): PipelineRepositoryInterface
    {
        return $this->app->make(PipelineRepositoryInterface::class);
    }

    /**
     * Create instance of task repository
     * @return TaskRepositoryInterface
     */
    protected function taskRepository(): TaskRepositoryInterface
    {
        return $this->app->make(TaskRepositoryInterface::class);
    }

    /**
     * Create complete pipeline to test against given relations
     * @param string|null $serviceUUID
     * @param string|null $actionUUID
     * @param string|null $taskUUID
     * @param string|null $pipelineUUID
     * @return void
     */
    protected function createCompletePipeline(?string $serviceUUID = null, ?string $actionUUID = null, ?string $taskUUID = null, ?string $pipelineUUID = null)
    {
        if (!$serviceUUID) {
            $serviceUUID = self::$serviceUUID;
        }
        if (!$actionUUID) {
            $actionUUID = self::$actionUUID;
        }
        if (!$taskUUID) {
            $taskUUID = self::$taskUUID;
        }
        if (!$pipelineUUID) {
            $pipelineUUID = self::$pipelineUUID;
        }

        $service = Service::create([
            'uuid'          =>  $serviceUUID,
            'identifier'    =>  'ccm-example.development-127.0.0.1',
            'service'       =>  'ccm',
            'address'       =>  '127.0.0.1',
            'port'          =>  32175,
            'datacenter'    =>  'leads',
            'tags'          =>  [],
            'meta'          =>  [],
            'online'        =>  1,
            'environment'   =>  'development',
        ]);

        $actionData = Arr::get($this->actionModelDataProvider(), 'example_action_entity.data');
        $action = Action::create([
            'uuid'              =>  $actionUUID,
            'name'              =>  Arr::get($actionData, 'name'),
            'description'       =>  Arr::get($actionData, 'description'),
            'type'              =>  Arr::get($actionData, 'type'),
            'command'           =>  Arr::get($actionData, 'command'),
            'arguments'         =>  Arr::get($actionData, 'arguments'),
            'working_dir'       =>  Arr::get($actionData, 'working_dir'),
            'run_as'            =>  Arr::get($actionData, 'run_as'),
            'use_sudo'          =>  Arr::get($actionData, 'use_sudo'),
            'fail_on_error'     =>  Arr::get($actionData, 'fail_on_error'),
        ]);

        ActionHost::create([
            'action_uuid'       =>  $action->getUuid(),
            'service_uuid'      =>  $service->getUuid(),
        ]);

        ActionExecution::create([
            'action_uuid'       =>  $actionUUID,
            'task_uuid'         =>  $taskUUID,
            'pipeline_uuid'     =>  $pipelineUUID,
            'state'             =>  0,
        ]);

        $taskData = Arr::get($this->taskModelDataProvider(), 'example_task_entity.data');
        $task = Task::create([
            'uuid'          =>  $taskUUID,
            'name'          =>  Arr::get($taskData, 'name'),
            'description'   =>  Arr::get($taskData, 'description'),
            'type'          =>  Arr::get($taskData, 'type'),
        ]);

        TaskAction::create([
            'task_uuid'     =>  $task->getUuid(),
            'action_uuid'   =>  $action->getUuid(),
            'order'         =>  1,
        ]);

        $pipelineData = Arr::get($this->pipelineModelDataProvider(), 'example_pipeline_entity.data');
        $pipeline = Pipeline::create([
            'uuid'          =>  $pipelineUUID,
            'name'          =>  Arr::get($pipelineData, 'name'),
            'description'   =>  Arr::get($pipelineData, 'description'),
        ]);

        PipelineTask::create([
            'pipeline_uuid'     =>  $pipeline->getUuid(),
            'task_uuid'         =>  $task->getUuid(),
            'order'             =>  1,
        ]);
    }
}
