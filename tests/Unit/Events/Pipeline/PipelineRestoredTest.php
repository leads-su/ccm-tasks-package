<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Events\Pipeline;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Events\Pipeline\PipelineRestored;

/**
 * Class PipelineRestoredTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\Pipeline
 */
class PipelineRestoredTest extends AbstractPipelineEventTest
{
    /**
     * @inheritDoc
     */
    protected string $activeEventHandler = PipelineRestored::class;

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeRestored(array $data): void
    {
        $this->assertInstanceOf(PipelineRestored::class, $this->createClassInstance($data));
    }

    /**
     * @inheritDoc
     * @param array $data
     * @return PipelineRestored
     */
    protected function createClassInstance(array $data): PipelineRestored
    {
        return new $this->activeEventHandler(
            Arr::get($data, 'user'),
        );
    }
}
