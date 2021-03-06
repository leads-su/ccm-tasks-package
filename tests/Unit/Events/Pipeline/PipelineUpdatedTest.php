<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Events\Pipeline;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Events\Pipeline\PipelineUpdated;

/**
 * Class PipelineUpdatedTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\Pipeline
 */
class PipelineUpdatedTest extends AbstractPipelineEventTest
{
    /**
     * @inheritDoc
     */
    protected string $activeEventHandler = PipelineUpdated::class;

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeCreated(array $data): void
    {
        $this->assertInstanceOf(PipelineUpdated::class, $this->createClassInstance($data));
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfValidDataReturnedFromGetNameMethod(array $data): void
    {
        $this->assertEquals(Arr::get($data, 'name'), $this->createClassInstance($data)->getName());
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfValidDataReturnedFromGetDescriptionMethod(array $data): void
    {
        $this->assertEquals(Arr::get($data, 'description'), $this->createClassInstance($data)->getDescription());
    }

    /**
     * @inheritDoc
     * @param array $data
     * @return PipelineUpdated
     */
    protected function createClassInstance(array $data): PipelineUpdated
    {
        return new $this->activeEventHandler(
            Arr::get($data, 'name'),
            Arr::get($data, 'description'),
        );
    }
}
