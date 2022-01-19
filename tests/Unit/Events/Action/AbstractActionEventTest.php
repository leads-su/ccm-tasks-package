<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Events\Action;

use Illuminate\Support\Carbon;
use ConsulConfigManager\Tasks\Test\Unit\Events\AbstractEventTest;

/**
 * Class AbstractActionEventTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\Action
 */
abstract class AbstractActionEventTest extends AbstractEventTest
{
    /**
     * @inheritDoc
     */
    public function eventDataProvider(): array
    {
        return [
            'example_action'        =>  [
                'data'              =>  [
                    'uuid'          =>  '73f66d30-ad58-4641-8b25-05b245031b50',
                    'name'          =>  'Example Action',
                    'description'   =>  'Example Action Description',
                    'type'          =>  1,
                    'command'       =>  'php',
                    'arguments'     =>  ['test.php'],
                    'working_dir'   =>  '/home/cabinet',
                    'run_as'        =>  'cabinet',
                    'use_sudo'      =>  false,
                    'fail_on_error' =>  true,
                    'time'          =>  Carbon::now(),
                    'user'          =>  $this->userInformation(),

                ],
            ],
        ];
    }
}
