<?php

namespace ConsulConfigManager\Tasks\Test\backup\Events\Pipeline;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Events\Pipeline\PipelineCreated;

/**
 * Class PipelineCreatedTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\Pipeline
 */
class PipelineCreatedTest extends AbstractPipelineEventTest
{
    /**
     * @inheritDoc
     */
    protected string $activeEventHandler = PipelineCreated::class;

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeCreated(array $data): void
    {
        $this->assertInstanceOf(PipelineCreated::class, $this->createClassInstance($data));
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
     * @return PipelineCreated
     */
    protected function createClassInstance(array $data): PipelineCreated
    {
        return new $this->activeEventHandler(
            Arr::get($data, 'name'),
            Arr::get($data, 'description'),
        );
    }
}
