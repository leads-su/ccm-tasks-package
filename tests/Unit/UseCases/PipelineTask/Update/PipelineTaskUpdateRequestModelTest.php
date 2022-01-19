<?php

namespace ConsulConfigManager\Tasks\Test\backup\UseCases\PipelineTask\Update;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\Http\Requests\PipelineTask\PipelineTaskCreateUpdateRequest;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Update\PipelineTaskUpdateRequestModel;

/**
 * Class PipelineTaskUpdateRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineTask\Update
 */
class PipelineTaskUpdateRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = new PipelineTaskCreateUpdateRequest();
        $instance = new PipelineTaskUpdateRequestModel($request, 1, 1);
        $this->assertSame($request, $instance->getRequest());
    }
}
