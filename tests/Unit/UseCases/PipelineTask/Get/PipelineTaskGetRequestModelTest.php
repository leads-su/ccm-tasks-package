<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineTask\Get;

use function request;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Get\PipelineTaskGetRequestModel;

/**
 * Class PipelineTaskGetRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineTask\Get
 */
class PipelineTaskGetRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new PipelineTaskGetRequestModel($request, 1, 1);
        $this->assertSame($request, $instance->getRequest());
    }
}
