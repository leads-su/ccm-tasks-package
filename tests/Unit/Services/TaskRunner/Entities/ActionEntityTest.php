<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Entities;

use ConsulConfigManager\Tasks\Enums\ExecutionState;
use ConsulConfigManager\Tasks\Models\ActionExecutionLog;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\ActionEntity;

/**
 * Class ActionEntityTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Entities
 */
class ActionEntityTest extends AbstractEntityTest
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceCanBeCreated(): void
    {
        $instance = $this->createInstance();
        $this->assertInstanceOf(ActionEntity::class, $instance);
    }

    /**
     * @return void
     */
    public function testShouldPassIfGetExecutionMethodReturnsSame(): void
    {
        $instance = $this->createInstance();
        $this->assertSameActionExecution($instance->getExecution());
    }

    /**
     * @return void
     */
    public function testShouldPassIfSetExecutionMethodCanBeUsed(): void
    {
        $instance = $this->createInstance();
        $instance->setExecution($this->getActionExecutionInstance());
        $this->assertSameActionExecution($instance->getExecution());
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
    public function testShouldPassIfValidDataReturnedFromSetExecutionStateMethodWithIgnoredStates(): void
    {
        $instance = $this->createInstance();
        $instance->setExecutionState(ExecutionState::SUCCESS);
        $instance->setExecutionState(ExecutionState::CANCELED);
        $this->assertSame(ExecutionState::SUCCESS, $instance->getExecutionState());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetExecutionLogMethod(): void
    {
        $instance = $this->createInstance();
        $instance->setExecutionLog(0, [
            'Hello World',
        ]);

        $model = ActionExecutionLog::find(1);
        $this->assertSame(0, $model->getExitCode());
        $this->assertSame([
            'Hello World',
        ], $model->getOutput());
    }

    /**
     * @return void
     */
    public function testShouldPassIfGetRunnerMethodReturnsSame(): void
    {
        $instance = $this->createInstance();
        $this->assertSameRunner($instance->getRunner());
    }

    /**
     * @return void
     */
    public function testShouldPassIfSetRunnerMethodCanBeUsed(): void
    {
        $instance = $this->createInstance();
        $instance->setRunner($this->createAndGetRemoteTask());
        $this->assertSameRunner($instance->getRunner());
    }

    /**
     * @return void
     */
    public function testShouldPassIfGetActionMethodReturnsSame(): void
    {
        $instance = $this->createInstance();
        $instance->bootstrap();
        $this->assertSameAction($instance->getAction());
    }

    /**
     * @return void
     */
    public function testShouldPassIfSetActionMethodCanBeUsed(): void
    {
        $instance = $this->createInstance();
        $instance->setAction($this->getActionInstance());
        $this->assertSameAction($instance->getAction());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromShouldFailOnErrorMethod(): void
    {
        $instance = $this->createInstance();
        $instance->bootstrap();
        $this->assertSame(true, $instance->shouldFailOnError());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromToArrayMethod(): void
    {
        $instance = $this->createInstance();
        $instance->bootstrap();

        $this->assertSameActionEntityToArray($instance);
    }

    /**
     * Create new instance of action entity
     * @return ActionEntity
     */
    private function createInstance(): ActionEntity
    {
        $this->createAndGetAction();
        return new ActionEntity(
            $this->createAndGetActionExecution(),
            $this->createAndGetRemoteTask(),
        );
    }
}
