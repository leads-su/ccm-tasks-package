<?php

namespace ConsulConfigManager\Tasks\Test\backup\Events\Pipeline;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Events\Pipeline\PipelineDeleted;

/**
 * Class PipelineDeletedTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\Pipeline
 */
class PipelineDeletedTest extends AbstractPipelineEventTest
{
    /**
     * @inheritDoc
     */
    protected string $activeEventHandler = PipelineDeleted::class;

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeDeleted(array $data): void
    {
        $this->assertInstanceOf(PipelineDeleted::class, $this->createClassInstance($data));
    }

    /**
     * @inheritDoc
     * @param array $data
     * @return PipelineDeleted
     */
    protected function createClassInstance(array $data): PipelineDeleted
    {
        return new $this->activeEventHandler(
            Arr::get($data, 'user'),
        );
    }
}
