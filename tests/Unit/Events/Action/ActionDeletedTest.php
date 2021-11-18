<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Events\Action;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Events\Action\ActionDeleted;

/**
 * Class ActionDeletedTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\Action
 */
class ActionDeletedTest extends AbstractActionEventTest
{
    /**
     * @inheritDoc
     */
    protected string $activeEventHandler = ActionDeleted::class;

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeDeleted(array $data): void
    {
        $this->assertInstanceOf(ActionDeleted::class, $this->createClassInstance($data));
    }

    /**
     * @inheritDoc
     * @param array $data
     * @return ActionDeleted
     */
    protected function createClassInstance(array $data): ActionDeleted
    {
        return new $this->activeEventHandler(
            Arr::get($data, 'user'),
        );
    }
}
