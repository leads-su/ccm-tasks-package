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
use ConsulConfigManager\Tasks\Models\TaskExecution;
use ConsulConfigManager\Consul\Agent\Models\Service;
use ConsulConfigManager\Tasks\Models\ActionExecution;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Models\PipelineExecution;
use ConsulConfigManager\Tasks\Models\ActionExecutionLog;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionHostInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskActionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionLogInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionRepositoryInterface;

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
     * Abstract Model Test Static: Pipeline Execution UUID
     * UUID used to create models suitable for relations querying
     * @var string
     */
    protected static string $pipelineExecutionUUID = '0062eb34-62dc-41b7-a0f3-097d5e7da889';

    /**
     * Action Execution model data provider
     * @return \array[][]
     */
    protected function actionExecutionModelDataProvider(): array
    {
        return [
            'example_action_execution_entry'    =>  [
                'data'                          =>  [
                    'id'                        =>  1,
                    'server_uuid'               =>  self::$serviceUUID,
                    'action_uuid'               =>  self::$actionUUID,
                    'task_uuid'                 =>  self::$taskUUID,
                    'pipeline_uuid'             =>  self::$pipelineUUID,
                    'pipeline_execution_uuid'   =>  self::$pipelineExecutionUUID,
                    'state'                     =>  1,
                ],
            ],
        ];
    }

    /**
     * Action Execution Log model data provider
     * @return \array[][]
     */
    protected function actionExecutionLogModelDataProvider(): array
    {
        return [
            'example_action_execution_log_entry'    =>  [
                'data'                              =>  [
                    'id'                            =>  1,
                    'action_execution_id'           =>  1,
                    'exit_code'                     =>  0,
                    'output'                        =>  [
                        [
                            'message'               =>  'Hello World',
                            'timestamp'             =>  1642592730,
                        ],
                        [
                            'message'               =>  'exit status 0',
                            'timestamp'             =>  1642592731,
                        ],
                    ],
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
                    'id'                        =>  1,
                    'action_uuid'               =>  self::$actionUUID,
                    'service_uuid'              =>  self::$serviceUUID,
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
                    'id'                            =>  1,
                    'uuid'                          =>  self::$pipelineExecutionUUID,
                    'pipeline_uuid'                 =>  self::$pipelineUUID,
                    'state'                         =>  1,
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
                    'uuid'                  =>  '49bf6ae6-d1e0-485f-bf34-db4df39f13be',
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
                    'uuid'                  =>  '8263d209-6d45-43aa-a410-817cfdcf07d9',
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
                    'fail_on_error' =>  false,
                ],
            ],
        ];
    }

    /**
     * Task execution model data provider
     * @return \array[][]
     */
    protected function taskExecutionModelDataProvider(): array
    {
        return [
            'example_task_execution_entity'     =>  [
                'data'                          =>  [
                    'id'                        =>  1,
                    'task_uuid'                 =>  self::$taskUUID,
                    'pipeline_uuid'             =>  self::$pipelineUUID,
                    'pipeline_execution_uuid'   =>  self::$pipelineExecutionUUID,
                    'state'                     =>  1,
                ],
            ],
        ];
    }

    /**
     * Create action execution model instance
     * @param array $data
     * @param bool $create
     * @return ActionExecutionInterface
     */
    protected function actionExecutionModel(array $data, bool $create = false): ActionExecutionInterface
    {
        if ($create) {
            return ActionExecution::factory()->create($data);
        }
        return ActionExecution::factory()->make($data);
    }

    /**
     * Create action execution log model instance
     * @param array $data
     * @param bool $create
     * @return ActionExecutionLogInterface
     */
    protected function actionExecutionLogModel(array $data, bool $create = false): ActionExecutionLogInterface
    {
        if ($create) {
            return ActionExecutionLog::factory()->create($data);
        }
        return ActionExecutionLog::factory()->make($data);
    }

    /**
     * Create action host model instance
     * @param array $data
     * @param bool $create
     * @return ActionHostInterface
     */
    protected function actionHostModel(array $data, bool $create = false): ActionHostInterface
    {
        if ($create) {
            return ActionHost::factory()->create($data);
        }
        return ActionHost::factory()->make($data);
    }

    /**
     * Create action model instance
     * @param array $data
     * @param bool $create
     * @return ActionInterface
     */
    protected function actionModel(array $data, bool $create = false): ActionInterface
    {
        if ($create) {
            return Action::factory()->create($data);
        }
        return Action::factory()->make($data);
    }

    /**
     * Create pipeline execution model instance
     * @param array $data
     * @param bool $create
     * @return PipelineExecutionInterface
     */
    protected function pipelineExecutionModel(array $data, bool $create = false): PipelineExecutionInterface
    {
        if ($create) {
            return PipelineExecution::factory()->create($data);
        }
        return PipelineExecution::factory()->make($data);
    }

    /**
     * Create pipeline task model instance
     * @param array $data
     * @param bool $create
     * @return PipelineTaskInterface
     */
    protected function pipelineTaskModel(array $data, bool $create = false): PipelineTaskInterface
    {
        if ($create) {
            return PipelineTask::factory()->create($data);
        }
        return PipelineTask::factory()->make($data);
    }

    /**
     * Create pipeline model instance
     * @param array $data
     * @param bool $create
     * @return PipelineInterface
     */
    protected function pipelineModel(array $data, bool $create = false): PipelineInterface
    {
        if ($create) {
            return Pipeline::factory()->create($data);
        }
        return Pipeline::factory()->make($data);
    }

    /**
     * Create task action model instance
     * @param array $data
     * @param bool $create
     * @return TaskActionInterface
     */
    protected function taskActionModel(array $data, bool $create = false): TaskActionInterface
    {
        if ($create) {
            return TaskAction::factory()->create($data);
        }
        return TaskAction::factory()->make($data);
    }

    /**
     * Create task execution model instance
     * @param array $data
     * @param bool $create
     * @return TaskExecutionInterface
     */
    protected function taskExecutionModel(array $data, bool $create = false): TaskExecutionInterface
    {
        if ($create) {
            return TaskExecution::factory()->create($data);
        }
        return TaskExecution::factory()->make($data);
    }

    /**
     * Create task model instance
     * @param array $data
     * @param bool $create
     * @return TaskInterface
     */
    protected function taskModel(array $data, bool $create = false): TaskInterface
    {
        if ($create) {
            return Task::factory()->create($data);
        }
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
     * Create instance of pipeline execution repository
     * @return PipelineExecutionRepositoryInterface
     */
    protected function pipelineExecutionRepository(): PipelineExecutionRepositoryInterface
    {
        return $this->app->make(PipelineExecutionRepositoryInterface::class);
    }

    /**
     * Create complete pipeline to test against given relations
     * @param string|null $serviceUUID
     * @param string|null $actionUUID
     * @param string|null $taskUUID
     * @param string|null $pipelineUUID
     * @param string|null $pipelineExecutionUUID
     * @return void
     */
    protected function createCompletePipeline(
        ?string $serviceUUID = null,
        ?string $actionUUID = null,
        ?string $taskUUID = null,
        ?string $pipelineUUID = null,
        ?string $pipelineExecutionUUID = null
    ) {
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
        if (!$pipelineExecutionUUID) {
            $pipelineExecutionUUID = self::$pipelineExecutionUUID;
        }

        Service::create([
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
        $this->actionModel($actionData, true);

        $this->actionHostModel([
            'action_uuid'       =>  $actionUUID,
            'service_uuid'      =>  $serviceUUID,
        ], true);

        $this->actionExecutionLogModel([
            'action_execution_id'           =>  1,
            'exit_code'                     =>  0,
            'output'                        =>  [
                [
                    'message'               =>  'Hello World',
                    'timestamp'             =>  1642592730,
                ],
                [
                    'message'               =>  'exit status 0',
                    'timestamp'             =>  1642592731,
                ],
            ],
        ], true);

        $this->actionExecutionModel([
            'server_uuid'               =>  $serviceUUID,
            'action_uuid'               =>  $actionUUID,
            'task_uuid'                 =>  $taskUUID,
            'pipeline_uuid'             =>  $pipelineUUID,
            'pipeline_execution_uuid'   =>  $pipelineExecutionUUID,
            'state'                     =>  0,
        ], true);

        $taskData = Arr::get($this->taskModelDataProvider(), 'example_task_entity.data');
        $this->taskModel([
            'uuid'          =>  $taskUUID,
            'name'          =>  Arr::get($taskData, 'name'),
            'description'   =>  Arr::get($taskData, 'description'),
            'type'          =>  Arr::get($taskData, 'type'),
            'fail_on_error' =>  Arr::get($taskData, 'fail_on_error'),
        ], true);

        $this->taskActionModel([
            'uuid'          =>  '8263d209-6d45-43aa-a410-817cfdcf07d9',
            'task_uuid'     =>  $taskUUID,
            'action_uuid'   =>  $actionUUID,
            'order'         =>  1,
        ], true);

        $this->taskExecutionModel([
            'task_uuid'                 =>  $taskUUID,
            'pipeline_uuid'             =>  $pipelineUUID,
            'pipeline_execution_uuid'   =>  $pipelineExecutionUUID,
            'state'                     =>  0,
        ], true);

        $pipelineData = Arr::get($this->pipelineModelDataProvider(), 'example_pipeline_entity.data');
        $this->pipelineModel([
            'uuid'          =>  $pipelineUUID,
            'name'          =>  Arr::get($pipelineData, 'name'),
            'description'   =>  Arr::get($pipelineData, 'description'),
        ], true);

        $this->pipelineTaskModel([
            'uuid'                  =>  '814089e0-003f-498b-9dea-b027c819622e',
            'pipeline_uuid'         =>  $pipelineUUID,
            'task_uuid'             =>  $taskUUID,
            'order'                 =>  1,
        ], true);

        $pipelineExecutionData = Arr::get($this->pipelineExecutionModelDataProvider(), 'example_pipeline_execution_entity.data');
        $this->pipelineExecutionModel([
            'uuid'                  =>  $pipelineExecutionUUID,
            'pipeline_uuid'         =>  Arr::get($pipelineExecutionData, 'pipeline_uuid'),
            'state'                 =>  Arr::get($pipelineExecutionData, 'state'),
        ], true);
    }
}
