<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Models;

use ConsulConfigManager\Tasks\Models\Task;
use ConsulConfigManager\Tasks\Models\Action;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\Models\Pipeline;
use ConsulConfigManager\Tasks\Models\ActionHost;
use ConsulConfigManager\Tasks\Models\TaskAction;
use ConsulConfigManager\Tasks\Models\PipelineTask;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Models\PipelineExecution;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionHostInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskActionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;
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
     * Action host model data provider
     * @see ActionHost
     * @return \string[][][]
     */
    protected function actionHostModelDataProvider(): array
    {
        return [
            'example_action_host_entity'        =>  [
                'data'                          =>  [
                    'action_uuid'               =>  '455af69e-1d2f-49dd-b626-c86d2427fcbf',
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
                    'uuid'              =>  '73f66d30-ad58-4641-8b25-05b245031b50',
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
                    'pipeline_uuid'         =>  '58245b27-8b9c-4ecf-a621-8941f3aaea80',
                    'task_uuid'             =>  '97580722-8f40-4b4f-b0f4-7da9c9f77af0',
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
                    'uuid'              =>  '73f66d30-ad58-4641-8b25-05b245031b50',
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
                    'task_uuid'             =>  'e06f2bf4-2fed-439c-96d3-c0b98df50646',
                    'action_uuid'           =>  'ebce8aa2-c645-4811-967f-b5554a6c1df4',
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
                    'uuid'          =>  '73f66d30-ad58-4641-8b25-05b245031b50',
                    'name'          =>  'Example Task',
                    'description'   =>  'Example Task Description',
                    'type'          =>  1,
                ],
            ],
        ];
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
}
