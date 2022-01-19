<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Events\Task;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Events\Task\TaskRestored;

/**
 * Class TaskRestoredTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\Task
 */
class TaskRestoredTest extends AbstractTaskEventTest
{
    /**
     * @inheritDoc
     */
    protected string $activeEventHandler = TaskRestored::class;

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeRestored(array $data): void
    {
        $this->assertInstanceOf(TaskRestored::class, $this->createClassInstance($data));
    }

    /**
     * @inheritDoc
     * @param array $data
     * @return TaskRestored
     */
    protected function createClassInstance(array $data): TaskRestored
    {
        return new $this->activeEventHandler(
            Arr::get($data, 'user'),
        );
    }
}
