<?php

namespace ConsulConfigManager\Tasks\Test\backup\Events\Task;

use Illuminate\Support\Carbon;
use ConsulConfigManager\Tasks\Test\backup\Events\AbstractEventTest;

/**
 * Class AbstractTaskEventTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\Task
 */
abstract class AbstractTaskEventTest extends AbstractEventTest
{
    /**
     * @inheritDoc
     */
    public function eventDataProvider(): array
    {
        return [
            'example_task'          =>  [
                'data'              =>  [
                    'uuid'          =>  '73f66d30-ad58-4641-8b25-05b245031b50',
                    'name'          =>  'Example Task',
                    'description'   =>  'Example Task Description',
                    'type'          =>  1,
                    'time'          =>  Carbon::now(),
                    'user'          =>  $this->userInformation(),
                ],
            ],
        ];
    }
}
