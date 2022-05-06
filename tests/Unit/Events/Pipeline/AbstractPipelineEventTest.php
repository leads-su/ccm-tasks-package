<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Events\Pipeline;

use Illuminate\Support\Carbon;
use ConsulConfigManager\Tasks\Test\Unit\Events\AbstractEventTest;

/**
 * Class AbstractPipelineEventTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\Pipeline
 */
abstract class AbstractPipelineEventTest extends AbstractEventTest
{
    /**
     * @inheritDoc
     */
    public function eventDataProvider(): array
    {
        return [
            'example_pipeline'      =>  [
                'data'              =>  [
                    'uuid'          =>  '73f66d30-ad58-4641-8b25-05b245031b50',
                    'name'          =>  'Example Pipeline',
                    'description'   =>  'Example Pipeline Description',
                    'time'          =>  Carbon::now(),
                    'user'          =>  $this->userInformation(),
                ],
            ],
        ];
    }
}
