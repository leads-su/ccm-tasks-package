<?php

namespace ConsulConfigManager\Tasks\Test\backup\Events\TaskAction;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Events\TaskAction\TaskActionRestored;

/**
 * Class TaskActionRestoredTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\TaskAction
 */
class TaskActionRestoredTest extends AbstractTaskActionEventTest
{
    /**
     * @inheritDoc
     */
    protected string $activeEventHandler = TaskActionRestored::class;

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeRestored(array $data): void
    {
        $this->assertInstanceOf(TaskActionRestored::class, $this->createClassInstance($data));
    }

    /**
     * @inheritDoc
     * @param array $data
     * @return TaskActionRestored
     */
    protected function createClassInstance(array $data): TaskActionRestored
    {
        return new $this->activeEventHandler(
            Arr::get($data, 'user'),
        );
    }
}
