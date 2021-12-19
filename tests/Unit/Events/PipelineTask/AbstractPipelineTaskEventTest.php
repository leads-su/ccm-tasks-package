<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Events\PipelineTask;

use Illuminate\Support\Carbon;
use ConsulConfigManager\Tasks\Test\Unit\Events\AbstractEventTest;

/**
 * Class AbstractPipelineTaskEventTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\PipelineTask
 */
abstract class AbstractPipelineTaskEventTest extends AbstractEventTest
{
    /**
     * @inheritDoc
     */
    public function eventDataProvider(): array
    {
        return [
            'example_pipeline_task' =>  [
                'data'              =>  [
                    'pipeline_uuid' =>  '1e882d64-9460-4bc2-96c5-bed4adbb4fa6',
                    'task_uuid'     =>  '73f66d30-ad58-4641-8b25-05b245031b50',
                    'order'         =>  1,
                    'time'          =>  Carbon::now(),
                    'user'          =>  $this->userInformation(),
                ],
            ],
        ];
    }
}
