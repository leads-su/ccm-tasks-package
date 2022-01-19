<?php

namespace ConsulConfigManager\Tasks\Test\backup\Events\PipelineTask;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Events\PipelineTask\PipelineTaskUpdated;

/**
 * Class PipelineTaskUpdatedTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\PipelineTask
 */
class PipelineTaskUpdatedTest extends AbstractPipelineTaskEventTest
{
    /**
     * @inheritDoc
     */
    protected string $activeEventHandler = PipelineTaskUpdated::class;

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeUpdated(array $data): void
    {
        $this->assertInstanceOf(PipelineTaskUpdated::class, $this->createClassInstance($data));
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
     * @return PipelineTaskUpdated
     */
    protected function createClassInstance(array $data): PipelineTaskUpdated
    {
        return new $this->activeEventHandler(
            Arr::get($data, 'order'),
            Arr::get($data, 'user'),
        );
    }
}
