<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Events\Task;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Events\Task\TaskDeleted;

/**
 * Class TaskDeletedTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\Task
 */
class TaskDeletedTest extends AbstractTaskEventTest
{
    /**
     * @inheritDoc
     */
    protected string $activeEventHandler = TaskDeleted::class;

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeDeleted(array $data): void
    {
        $this->assertInstanceOf(TaskDeleted::class, $this->createClassInstance($data));
    }

    /**
     * @inheritDoc
     * @param array $data
     * @return TaskDeleted
     */
    protected function createClassInstance(array $data): TaskDeleted
    {
        return new $this->activeEventHandler(
            Arr::get($data, 'user'),
        );
    }
}
