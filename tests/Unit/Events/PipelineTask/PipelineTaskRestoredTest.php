<?php

namespace ConsulConfigManager\Tasks\Test\backup\Events\PipelineTask;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Events\PipelineTask\PipelineTaskRestored;

/**
 * Class PipelineTaskRestoredTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\PipelineTask
 */
class PipelineTaskRestoredTest extends AbstractPipelineTaskEventTest
{
    /**
     * @inheritDoc
     */
    protected string $activeEventHandler = PipelineTaskRestored::class;

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeRestored(array $data): void
    {
        $this->assertInstanceOf(PipelineTaskRestored::class, $this->createClassInstance($data));
    }

    /**
     * @inheritDoc
     * @param array $data
     * @return PipelineTaskRestored
     */
    protected function createClassInstance(array $data): PipelineTaskRestored
    {
        return new $this->activeEventHandler(
            Arr::get($data, 'user'),
        );
    }
}
