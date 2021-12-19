<?php

namespace ConsulConfigManager\Tasks\Test\Feature;

use Illuminate\Support\Str;
use Tightenco\Collect\Support\Arr;
use Illuminate\Testing\TestResponse;
use ConsulConfigManager\Tasks\Models\Task;
use ConsulConfigManager\Tasks\Models\Action;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\Enums\TaskType;
use ConsulConfigManager\Tasks\Models\Pipeline;
use ConsulConfigManager\Tasks\Enums\ActionType;
use ConsulConfigManager\Tasks\Models\TaskAction;
use ConsulConfigManager\Tasks\Models\PipelineTask;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskActionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskInterface;

/**
 * Class AbstractFeatureTest
 * @package ConsulConfigManager\Tasks\Test\Feature
 */
abstract class AbstractFeatureTest extends TestCase
{
    /**
     * Create new action model
     * @return TestResponse
     */
    protected function createAndGetAction(): TestResponse
    {
        return $this->post('/task-manager/actions', [
            'name'              =>  'Example Action',
            'description'       =>  'This is description for example action',
            'type'              =>  ActionType::LOCAL,
            'command'           =>  'lsb_release',
            'arguments'         =>  ['-a'],
            'working_dir'       =>  null,
            'run_as'            =>  null,
            'use_sudo'          =>  false,
            'fail_on_error'     =>  true,
        ]);
    }

    /**
     * Create new pipeline model
     * @return TestResponse
     */
    protected function createAndGetPipeline(): TestResponse
    {
        return $this->post('/task-manager/pipelines', [
            'name'              =>  'Example Pipeline',
            'description'       =>  'This is description for example pipeline',
        ]);
    }

    /**
     * Create new task model
     * @return TestResponse
     */
    protected function createAndGetTask(): TestResponse
    {
        return $this->post('/task-manager/tasks', [
            'name'              =>  'Example Task',
            'description'       =>  'This is description for example task',
            'type'              =>  TaskType::LOCAL,
        ]);
    }

    /**
     * Create and get task action
     * @param bool $failTaskCreation
     * @param bool $failActionCreation
     * @return TestResponse
     */
    protected function createAndGetTaskAction(bool $failTaskCreation = false, bool $failActionCreation = false): TestResponse
    {
        $action = $this->createAndGetAction()->json('data');
        $task = $this->createAndGetTask()->json('data');

        if ($failTaskCreation) {
            Arr::set($task, 'uuid', Str::uuid()->toString());
        }

        if ($failActionCreation) {
            Arr::set($action, 'uuid', Str::uuid()->toString());
        }

        $response = $this->post('/task-manager/tasks/' . Arr::get($task, 'uuid') . '/actions', [
            'action_uuid'       =>  Arr::get($action, 'uuid'),
            'order'             =>  1,
        ]);

        /**
         * @var TaskInterface $createdTask
         */
        $createdTask = Task::all()->first();

        /**
         * @var ActionInterface $createdAction
         */
        $createdAction = Action::all()->first();

        /**
         * @var TaskActionInterface $createdTaskAction
         */
        $createdTaskAction = TaskAction::all()->first();

        $this->assertSame($createdTask->getUuid(), $createdTaskAction->getTaskUuid());
        $this->assertSame($createdAction->getUuid(), $createdTaskAction->getActionUuid());

        return $response;
    }

    /**
     * Create and get task action with ids instead of uuids
     * @param bool $failTaskCreation
     * @param bool $failActionCreation
     * @return TestResponse
     */
    protected function createAndGetTaskActionWithIDs(bool $failTaskCreation = false, bool $failActionCreation = false): TestResponse
    {
        $action = $this->createAndGetAction()->json('data');
        $task = $this->createAndGetTask()->json('data');

        if ($failTaskCreation) {
            Arr::set($task, 'id', 2);
        }

        if ($failActionCreation) {
            Arr::set($action, 'id', 2);
        }

        $response =  $this->post('/task-manager/tasks/' . Arr::get($task, 'id') . '/actions', [
            'action_uuid'       =>  Arr::get($action, 'id'),
            'order'             =>  1,
        ]);

        if (!$failTaskCreation && !$failActionCreation) {
            /**
             * @var TaskInterface $createdTask
             */
            $createdTask = Task::all()->first();

            /**
             * @var ActionInterface $createdAction
             */
            $createdAction = Action::all()->first();

            /**
             * @var TaskActionInterface $createdTaskAction
             */
            $createdTaskAction = TaskAction::all()->first();

            $this->assertSame($createdTask->getUuid(), $createdTaskAction->getTaskUuid());
            $this->assertSame($createdAction->getUuid(), $createdTaskAction->getActionUuid());
        }

        return $response;
    }

    /**
     * Create and get pipeline task
     * @param bool $failPipelineCreation
     * @param bool $failTaskCreation
     * @return TestResponse
     */
    protected function createAndGetPipelineTask(bool $failPipelineCreation = false, bool $failTaskCreation = false): TestResponse
    {
        $task = $this->createAndGetTask()->json('data');
        $pipeline = $this->createAndGetPipeline()->json('data');

        if ($failPipelineCreation) {
            Arr::set($pipeline, 'uuid', Str::uuid()->toString());
        }

        if ($failTaskCreation) {
            Arr::set($task, 'uuid', Str::uuid()->toString());
        }

        $response = $this->post('/task-manager/pipelines/' . Arr::get($pipeline, 'uuid') . '/tasks', [
            'task_uuid'         =>  Arr::get($task, 'uuid'),
            'order'             =>  1,
        ]);

        /**
         * @var PipelineInterface $createdPipeline
         */
        $createdPipeline = Pipeline::all()->first();

        /**
         * @var TaskInterface $createdTask
         */
        $createdTask = Task::all()->first();

        /**
         * @var PipelineTaskInterface $createdPipelineTask
         */
        $createdPipelineTask = PipelineTask::all()->first();

        $this->assertSame($createdPipeline->getUuid(), $createdPipelineTask->getPipelineUuid());
        $this->assertSame($createdTask->getUuid(), $createdPipelineTask->getTaskUuid());

        return $response;
    }

    /**
     * Create and get pipeline task with ids instead of uuids
     * @param bool $failPipelineCreation
     * @param bool $failTaskCreation
     * @return TestResponse
     */
    protected function createAndGetPipelineTaskWithIDs(bool $failPipelineCreation = false, bool $failTaskCreation = false): TestResponse
    {
        $task = $this->createAndGetTask()->json('data');
        $pipeline = $this->createAndGetPipeline()->json('data');

        if ($failPipelineCreation) {
            Arr::set($pipeline, 'id', 2);
        }

        if ($failTaskCreation) {
            Arr::set($task, 'id', 2);
        }

        $response =  $this->post('/task-manager/pipelines/' . Arr::get($pipeline, 'id') . '/tasks', [
            'task_uuid'         =>  Arr::get($task, 'id'),
            'order'             =>  1,
        ]);

        if (!$failPipelineCreation && !$failTaskCreation) {
            /**
             * @var PipelineInterface $createdPipeline
             */
            $createdPipeline = Pipeline::all()->first();

            /**
             * @var TaskInterface $createdTask
             */
            $createdTask = Task::all()->first();

            /**
             * @var PipelineTaskInterface $createdPipelineTask
             */
            $createdPipelineTask = PipelineTask::all()->first();

            $this->assertSame($createdPipeline->getUuid(), $createdPipelineTask->getPipelineUuid());
            $this->assertSame($createdTask->getUuid(), $createdPipelineTask->getTaskUuid());
        }

        return $response;
    }
}
