<?php

namespace ConsulConfigManager\Tasks\Test\backup\Events\PipelineTask;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Events\PipelineTask\PipelineTaskCreated;

/**
 * Class PipelineTaskCreatedTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\PipelineTask
 */
class PipelineTaskCreatedTest extends AbstractPipelineTaskEventTest
{
    /**
     * @inheritDoc
     */
    protected string $activeEventHandler = PipelineTaskCreated::class;

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeCreated(array $data): void
    {
        $this->assertInstanceOf(PipelineTaskCreated::class, $this->createClassInstance($data));
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfValidDataReturnedFromGetPipelineMethod(array $data): void
    {
        $this->assertEquals(Arr::get($data, 'pipeline_uuid'), $this->createClassInstance($data)->getPipeline());
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfValidDataReturnedFromGetTaskMethod(array $data): void
    {
        $this->assertEquals(Arr::get($data, 'task_uuid'), $this->createClassInstance($data)->getTask());
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
     * @return PipelineTaskCreated
     */
    protected function createClassInstance(array $data): PipelineTaskCreated
    {
        return new $this->activeEventHandler(
            Arr::get($data, 'pipeline_uuid'),
            Arr::get($data, 'task_uuid'),
            Arr::get($data, 'order'),
        );
    }
}
