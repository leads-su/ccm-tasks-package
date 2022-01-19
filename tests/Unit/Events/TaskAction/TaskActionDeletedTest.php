<?php

namespace ConsulConfigManager\Tasks\Test\backup\Events\TaskAction;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Events\TaskAction\TaskActionDeleted;

/**
 * Class TaskActionDeletedTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\TaskAction
 */
class TaskActionDeletedTest extends AbstractTaskActionEventTest
{
    /**
     * @inheritDoc
     */
    protected string $activeEventHandler = TaskActionDeleted::class;

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeDeletedWithoutForce(array $data): void
    {
        $this->assertInstanceOf(TaskActionDeleted::class, $this->createClassInstance($data));
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeDeletedWithForce(array $data): void
    {
        $this->assertInstanceOf(TaskActionDeleted::class, $this->createClassInstance($data, true));
    }

    /**
     * @inheritDoc
     * @param array $data
     * @param bool $force
     * @return TaskActionDeleted
     */
    protected function createClassInstance(array $data, bool $force = false): TaskActionDeleted
    {
        return new $this->activeEventHandler(
            $force,
            Arr::get($data, 'user'),
        );
    }
}
