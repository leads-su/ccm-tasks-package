<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineExecution\Get;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\PipelineExecution\Get\PipelineExecutionGetRequestModel;

/**
 * Class PipelineExecutionGetRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineExecution\Get
 */
class PipelineExecutionGetRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new PipelineExecutionGetRequestModel($request, 'identifier', 1);
        $this->assertSame($request, $instance->getRequest());
        $this->assertSame('identifier', $instance->getIdentifier());
        $this->assertSame(1, $instance->getExecution());
    }
}
