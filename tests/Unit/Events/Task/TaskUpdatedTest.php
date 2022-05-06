<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Events\Task;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Events\Task\TaskUpdated;

/**
 * Class TaskUpdatedTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\Task
 */
class TaskUpdatedTest extends AbstractTaskEventTest
{
    /**
     * @inheritDoc
     */
    protected string $activeEventHandler = TaskUpdated::class;

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeCreated(array $data): void
    {
        $this->assertInstanceOf(TaskUpdated::class, $this->createClassInstance($data));
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
     * @inheritDoc
     * @param array $data
     * @return TaskUpdated
     */
    protected function createClassInstance(array $data): TaskUpdated
    {
        return new $this->activeEventHandler(
            Arr::get($data, 'name'),
            Arr::get($data, 'description')
        );
    }
}
