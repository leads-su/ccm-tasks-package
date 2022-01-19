<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Events\Action;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Events\Action\ActionRestored;

/**
 * Class ActionRestoredTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\Action
 */
class ActionRestoredTest extends AbstractActionEventTest
{
    /**
     * @inheritDoc
     */
    protected string $activeEventHandler = ActionRestored::class;

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeDeleted(array $data): void
    {
        $this->assertInstanceOf(ActionRestored::class, $this->createClassInstance($data));
    }

    /**
     * @inheritDoc
     * @param array $data
     * @return ActionRestored
     */
    protected function createClassInstance(array $data): ActionRestored
    {
        return new $this->activeEventHandler(
            Arr::get($data, 'user'),
        );
    }
}
