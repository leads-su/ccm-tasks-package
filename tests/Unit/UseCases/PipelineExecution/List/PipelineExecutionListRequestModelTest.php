<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineExecution\List;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\PipelineExecution\List\PipelineExecutionListRequestModel;

/**
 * Class PipelineExecutionListRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineExecution\List
 */
class PipelineExecutionListRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new PipelineExecutionListRequestModel($request, '113123132');
        $this->assertSame($request, $instance->getRequest());
        $this->assertSame('113123132', $instance->getIdentifier());
    }
}
