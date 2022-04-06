<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Entities;

use ConsulConfigManager\Tasks\Enums\ExecutionState;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\TaskEntity;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\ActionEntity;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\ServerEntity;

/**
 * Class TaskEntityTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Entities
 */
class TaskEntityTest extends AbstractEntityTest
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceCanBeCreated(): void
    {
        $instance = new TaskEntity($this->createAndGetTaskExecution());
        $this->assertInstanceOf(TaskEntity::class, $instance);
    }

    /**
     * @return void
     */
    public function testShouldPassIfGetExecutionMethodReturnsSame(): void
    {
        $instance = $this->createInstance();
        $this->assertSameTaskExecution($instance->getExecution());
    }

    /**
     * @return void
     */
    public function testShouldPassIfSetExecutionMethodCanBeUsed(): void
    {
        $instance = $this->createInstance();
        $instance->setExecution($this->getTaskExecutionInstance());
        $this->assertSameTaskExecution($instance->getExecution());
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
    public function testShouldPassIfGetTaskMethodReturnsSame(): void
    {
        $instance = $this->createInstance();
        $instance->bootstrap();
        $this->assertSameTask($instance->getTask());
    }

    /**
     * @return void
     */
    public function testShouldPassIfSetTaskMethodCanBeUsed(): void
    {
        $instance = $this->createInstance();
        $instance->setTask($this->getTaskInstance());
        $this->assertSameTask($instance->getTask());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromShouldFailOnErrorMethod(): void
    {
        $instance = $this->createInstance();
        $instance->bootstrap();
        $this->assertSame(false, $instance->shouldFailOnError());
    }

    /**
     * @return void
     */
    public function testShouldPassIfCanAddServerToTheTask(): void
    {
        $instance = $this->createInstance(true);
        $this->assertCount(1, $instance->getServers());
        $this->assertSameServerEntityToArray($instance->getServers()->first());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromFindServer(): void
    {
        $instance = $this->createInstance(true);
        $this->assertInstanceOf(ServerEntity::class, $instance->findServer($this->serverIdentifier));
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasServerWithValidServer(): void
    {
        $instance = $this->createInstance(true);
        $this->assertTrue($instance->hasServer($this->serverIdentifier));
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasServerWithInvalidServer(): void
    {
        $instance = $this->createInstance(true);
        $this->assertFalse($instance->hasServer('11111111-1111-1111-1111-111111111111'));
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasIncompleteActionsMethodWithCreatedState(): void
    {
        $instance = $this->createInstance(true, ExecutionState::CREATED);
        $this->assertTrue($instance->hasIncompleteActions());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasIncompleteActionsMethodWithWaitingState(): void
    {
        $instance = $this->createInstance(true, ExecutionState::WAITING);
        $this->assertTrue($instance->hasIncompleteActions());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasIncompleteActionsMethodWithExecutingState(): void
    {
        $instance = $this->createInstance(true, ExecutionState::EXECUTING);
        $this->assertTrue($instance->hasIncompleteActions());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasIncompleteActionsMethodWithCanceledState(): void
    {
        $instance = $this->createInstance(true, ExecutionState::CANCELED);
        $this->assertFalse($instance->hasIncompleteActions());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasIncompleteActionsMethodWithSuccessState(): void
    {
        $instance = $this->createInstance(true, ExecutionState::SUCCESS);
        $this->assertFalse($instance->hasIncompleteActions());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasIncompleteActionsMethodWithFailureState(): void
    {
        $instance = $this->createInstance(true, ExecutionState::FAILURE);
        $this->assertFalse($instance->hasIncompleteActions());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasFailedActionsMethodWithCreatedState(): void
    {
        $instance = $this->createInstance(true, ExecutionState::CREATED);
        $this->assertFalse($instance->hasFailedActions());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasFailedActionsMethodWithWaitingState(): void
    {
        $instance = $this->createInstance(true, ExecutionState::WAITING);
        $this->assertFalse($instance->hasFailedActions());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasFailedActionsMethodWithExecutingState(): void
    {
        $instance = $this->createInstance(true, ExecutionState::EXECUTING);
        $this->assertFalse($instance->hasFailedActions());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasFailedActionsMethodWithCanceledState(): void
    {
        $instance = $this->createInstance(true, ExecutionState::CANCELED);
        $this->assertFalse($instance->hasFailedActions());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasFailedActionsMethodWithSuccessState(): void
    {
        $instance = $this->createInstance(true, ExecutionState::SUCCESS);
        $this->assertFalse($instance->hasFailedActions());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHasFailedActionsMethodWithFailureState(): void
    {
        $instance = $this->createInstance(true, ExecutionState::FAILURE);
        $this->assertTrue($instance->hasFailedActions());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromToArrayMethod(): void
    {
        $instance = $this->createInstance(true);

        $this->assertSameTaskEntityToArray($instance);
    }

    /**
     * Create new task entity instance
     * @param bool $addServer
     * @param int $actionState
     * @return TaskEntity
     */
    private function createInstance(bool $addServer = false, int $actionState = ExecutionState::CREATED): TaskEntity
    {
        $this->createAndGetTask();
        $instance = new TaskEntity(
            $this->createAndGetTaskExecution()
        );

        if ($addServer) {
            $instance->bootstrap();
            $instance->addServer(
                $this->createServerEntityInstance($actionState)
            );
        }

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
            $this->createAndGetRemoteAction()
        );
        $actionEntity->bootstrap();
        return $actionEntity;
    }
}
