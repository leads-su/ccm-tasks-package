<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Events\PipelineTask;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Events\PipelineTask\PipelineTaskDeleted;

/**
 * Class PipelineTaskDeletedTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\PipelineTask
 */
class PipelineTaskDeletedTest extends AbstractPipelineTaskEventTest
{
    /**
     * @inheritDoc
     */
    protected string $activeEventHandler = PipelineTaskDeleted::class;

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeDeletedWithoutForce(array $data): void
    {
        $this->assertInstanceOf(PipelineTaskDeleted::class, $this->createClassInstance($data));
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeDeletedWithForce(array $data): void
    {
        $this->assertInstanceOf(PipelineTaskDeleted::class, $this->createClassInstance($data, true));
    }

    /**
     * @inheritDoc
     * @param array $data
     * @param bool $force
     * @return PipelineTaskDeleted
     */
    protected function createClassInstance(array $data, bool $force = false): PipelineTaskDeleted
    {
        return new $this->activeEventHandler(
            $force,
            Arr::get($data, 'user'),
        );
    }
}
