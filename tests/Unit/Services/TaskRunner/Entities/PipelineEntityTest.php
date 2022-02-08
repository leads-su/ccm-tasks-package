<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Entities;

use ConsulConfigManager\Tasks\Enums\ExecutionState;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\TaskEntity;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\ActionEntity;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\ServerEntity;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\PipelineEntity;

/**
 * Class PipelineEntityTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Entities
 */
class PipelineEntityTest extends AbstractEntityTest
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceCanBeCreated(): void
    {
        $instance = new PipelineEntity($this->createAndGetPipelineExecution());
        $this->assertInstanceOf(PipelineEntity::class, $instance);
    }

    /**
     * @return void
     */
    public function testShouldPassIfGetExecutionMethodReturnsSame(): void
    {
        $instance = $this->createInstance();
        $this->assertSamePipelineExecution($instance->getExecution());
    }

    /**
     * @return void
     */
    public function testShouldPassIfSetExecutionMethodCanBeUsed(): void
    {
        $instance = $this->createInstance();
        $instance->setExecution($this->getPipelineExecutionInstance());
        $this->assertSamePipelineExecution($instance->getExecution());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetExecutionStateMethod(): void
    {
        $instance = $this->createInstance();
        $this->assertSame(ExecutionState::CREATED, $instance->getExecutionState());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetExecutionStateMethod(): void
    {
        $instance = $this->createInstance();
        $instance->setExecutionState(ExecutionState::EXECUTING);
        $this->assertSame(ExecutionState::EXECUTING, $instance->getExecutionState());
    }

    /**
     * @return void
     */
    public function testShouldPassIfGetPipelineMethodReturnsSame(): void
    {
        $instance = $this->createInstance();
        $instance->bootstrap();
        $this->assertSamePipeline($instance->getPipeline());
    }

    /**
     * @return void
     */
    public function testShouldPassIfSetPipelineMethodCanBeUsed(): void
    {
        $instance = $this->createInstance();
        $instance->setPipeline($this->getPipelineInstance());
        $this->assertSamePipeline($instance->getPipeline());
    }

    /**
     * @return void
     */
    public function testShouldPassIfCanAddTaskToThePipeline(): void
    {
        $instance = $this->createInstance(true);
        $this->assertCount(1, $instance->getTasks());
        $this->assertSameTaskEntityToArray($instance->getTasks()->first());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromToArrayMethod(): void
    {
        $instance = $this->createInstance(true);

        $this->assertSamePipelineEntityToArray($instance);
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasIncompleteTasksMethodWithCreatedState(): void
    {
        $instance = $this->createInstance(true, ExecutionState::CREATED);
        $this->assertTrue($instance->hasIncompleteTasks());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasIncompleteTasksMethodWithWaitingState(): void
    {
        $instance = $this->createInstance(true, ExecutionState::WAITING);
        $this->assertTrue($instance->hasIncompleteTasks());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasIncompleteTasksMethodWithExecutingState(): void
    {
        $instance = $this->createInstance(true, ExecutionState::EXECUTING);
        $this->assertTrue($instance->hasIncompleteTasks());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasIncompleteTasksMethodWithCanceledState(): void
    {
        $instance = $this->createInstance(true, ExecutionState::CANCELED);
        $this->assertFalse($instance->hasIncompleteTasks());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasIncompleteTasksMethodWithSuccessState(): void
    {
        $instance = $this->createInstance(true, ExecutionState::SUCCESS);
        $this->assertFalse($instance->hasIncompleteTasks());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasIncompleteTasksMethodWithFailureState(): void
    {
        $instance = $this->createInstance(true, ExecutionState::FAILURE);
        $this->assertFalse($instance->hasIncompleteTasks());
    }

    /**
     * Create new pipeline entity instance
     * @param bool $addTask
     * @param int $executionState
     * @return PipelineEntity
     */
    private function createInstance(bool $addTask = false, int $executionState = ExecutionState::CREATED): PipelineEntity
    {
        $this->createAndGetPipeline();
        $instance = new PipelineEntity($this->createAndGetPipelineExecution());

        if ($addTask) {
            $instance->bootstrap();
            $instance->addTask(
                $this->createTaskEntityInstance($executionState)
            );
        }

        return $instance;
    }

    /**
     * Create new task entity instance
     * @param int $executionState
     * @return TaskEntity
     */
    private function createTaskEntityInstance(int $executionState = ExecutionState::CREATED): TaskEntity
    {
        $this->createAndGetTask();
        $instance = new TaskEntity(
            $this->createAndGetTaskExecution($executionState)
        );

        $instance->bootstrap();
        $instance->addServer(
            $this->createServerEntityInstance($executionState)
        );

        return $instance;
    }

    /**
     * Create new server entity instance
     * @param int $actionState
     * @return ServerEntity
     */
    private function createServerEntityInstance(int $actionState = ExecutionState::CREATED): ServerEntity
    {
        $this->createAndGetServer();
        $instance = new ServerEntity($this->serverIdentifier);
        $instance->bootstrap();

        $instance->addAction(
            $this->createActionEntityInstance($actionState)
        );

        return $instance;
    }

    /**
     * Create new action entity instance
     * @param int $state
     * @return ActionEntity
     */
    private function createActionEntityInstance(int $state = ExecutionState::CREATED): ActionEntity
    {
        $this->createAndGetAction();
        $actionEntity = new ActionEntity(
            $this->createAndGetActionExecution($state),
            $this->createAndGetRemoteTask()
        );
        $actionEntity->bootstrap();
        return $actionEntity;
    }
}
