<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Entities;

use ConsulConfigManager\Tasks\Enums\ExecutionState;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\ActionEntity;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\ServerEntity;

/**
 * Class ServerEntityTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Entities
 */
class ServerEntityTest extends AbstractEntityTest
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceCanBeCreated(): void
    {
        $instance = new ServerEntity($this->serverIdentifier);
        $this->assertInstanceOf(ServerEntity::class, $instance);
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetIdentifierMethod(): void
    {
        $instance = new ServerEntity($this->serverIdentifier);
        $this->assertSame($this->serverIdentifier, $instance->getIdentifier());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetIdentifierMethod(): void
    {
        $instance = new ServerEntity($this->serverIdentifier);
        $instance->setIdentifier('11111111-1111-1111-1111-111111111111');
        $this->assertSame('11111111-1111-1111-1111-111111111111', $instance->getIdentifier());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetServerMethod(): void
    {
        $instance = $this->createInstance();
        $instance->bootstrap();
        $this->assertSameServer($instance->getServer());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetServerMethod(): void
    {
        $instance = $this->createInstance();
        $instance->setServer($this->getServerInstance());
        $this->assertSameServer($instance->getServer());
    }

    /**
     * @return void
     */
    public function testShouldPassIfCanAddActionToTheServer(): void
    {
        $instance = $this->createInstance(true);
        $this->assertCount(1, $instance->getActions());
        $this->assertSameActionEntityToArray($instance->getActions()->first());
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

        $this->assertSameServerEntityToArray($instance);
    }

    /**
     * Create new instance of entity
     * @param bool $addAction
     * @param int $actionState
     * @return ServerEntity
     */
    private function createInstance(
        bool $addAction = false,
        int $actionState = ExecutionState::CREATED
    ): ServerEntity {
        $this->createAndGetServer();
        $instance = new ServerEntity($this->serverIdentifier);

        if ($addAction) {
            $instance->bootstrap();
            $instance->addAction($this->createActionEntityInstance($actionState));
        }

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
