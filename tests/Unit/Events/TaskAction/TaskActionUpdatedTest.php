<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Events\TaskAction;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Events\TaskAction\TaskActionUpdated;

/**
 * Class TaskActionUpdatedTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\TaskAction
 */
class TaskActionUpdatedTest extends AbstractTaskActionEventTest
{
    /**
     * @inheritDoc
     */
    protected string $activeEventHandler = TaskActionUpdated::class;

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeUpdated(array $data): void
    {
        $this->assertInstanceOf(TaskActionUpdated::class, $this->createClassInstance($data));
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
     * @return TaskActionUpdated
     */
    protected function createClassInstance(array $data): TaskActionUpdated
    {
        return new $this->activeEventHandler(
            Arr::get($data, 'order'),
            Arr::get($data, 'user'),
        );
    }
}
