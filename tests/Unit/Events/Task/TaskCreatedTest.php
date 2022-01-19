<?php

namespace ConsulConfigManager\Tasks\Test\backup\Events\Task;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Events\Task\TaskCreated;

/**
 * Class TaskCreatedTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\Task
 */
class TaskCreatedTest extends AbstractTaskEventTest
{
    /**
     * @inheritDoc
     */
    protected string $activeEventHandler = TaskCreated::class;

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeCreated(array $data): void
    {
        $this->assertInstanceOf(TaskCreated::class, $this->createClassInstance($data));
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfValidDataReturnedFromGetNameMethod(array $data): void
    {
        $this->assertEquals(Arr::get($data, 'name'), $this->createClassInstance($data)->getName());
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfValidDataReturnedFromGetDescriptionMethod(array $data): void
    {
        $this->assertEquals(Arr::get($data, 'description'), $this->createClassInstance($data)->getDescription());
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfValidDataReturnedFromGetTypeMethod(array $data): void
    {
        $this->assertEquals(Arr::get($data, 'type'), $this->createClassInstance($data)->getType());
    }

    /**
     * @inheritDoc
     * @param array $data
     * @return TaskCreated
     */
    protected function createClassInstance(array $data): TaskCreated
    {
        return new $this->activeEventHandler(
            Arr::get($data, 'name'),
            Arr::get($data, 'description'),
            Arr::get($data, 'type'),
        );
    }
}
