<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\Pipeline\Get;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\Models\Pipeline;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Get\PipelineGetResponseModel;

/**
 * Class PipelineGetResponseModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Pipeline\Get
 */
class PipelineGetResponseModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedWithPipelinePassedIsReturned(): void
    {
        $instance = new PipelineGetResponseModel(new Pipeline());
        $this->assertSame([], $instance->getEntity());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedWithArrayPassedIsReturned(): void
    {
        $instance = new PipelineGetResponseModel([]);
        $this->assertSame([], $instance->getEntity());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedWithNullPassedIsReturned(): void
    {
        $instance = new PipelineGetResponseModel(null);
        $this->assertSame([], $instance->getEntity());
    }
}
