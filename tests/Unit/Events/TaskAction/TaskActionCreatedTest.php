<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Events\TaskAction;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Events\TaskAction\TaskActionCreated;

/**
 * Class TaskActionCreatedTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\TaskAction
 */
class TaskActionCreatedTest extends AbstractTaskActionEventTest
{
    /**
     * @inheritDoc
     */
    protected string $activeEventHandler = TaskActionCreated::class;

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeCreated(array $data): void
    {
        $this->assertInstanceOf(TaskActionCreated::class, $this->createClassInstance($data));
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfValidDataReturnedFromGetTaskMethod(array $data): void
    {
        $this->assertEquals(Arr::get($data, 'task_uuid'), $this->createClassInstance($data)->getTask());
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfValidDataReturnedFromGetActionMethod(array $data): void
    {
        $this->assertEquals(Arr::get($data, 'action_uuid'), $this->createClassInstance($data)->getAction());
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfValidDataReturnedFromGetOrderMethod(array $data): void
    {
        $this->assertEquals(Arr::get($data, 'order'), $this->createClassInstance($data)->getOrder());
    }

    /**
     * @inheritDoc
     * @param array $data
     * @return TaskActionCreated
     */
    protected function createClassInstance(array $data): TaskActionCreated
    {
        return new $this->activeEventHandler(
            Arr::get($data, 'task_uuid'),
            Arr::get($data, 'action_uuid'),
            Arr::get($data, 'order'),
        );
    }
}
