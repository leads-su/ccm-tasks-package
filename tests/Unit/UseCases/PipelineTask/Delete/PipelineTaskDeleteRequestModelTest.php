<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineTask\Delete;

use function request;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Delete\PipelineTaskDeleteRequestModel;

/**
 * Class PipelineTaskDeleteRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineTask\Delete
 */
class PipelineTaskDeleteRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new PipelineTaskDeleteRequestModel($request, 1, 1);
        $this->assertSame($request, $instance->getRequest());
    }
}
