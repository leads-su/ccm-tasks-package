<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineHistory\List;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\PipelineHistory\List\PipelineHistoryListRequestModel;

/**
 * Class PipelineHistoryListRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineHistory\List
 */
class PipelineHistoryListRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new PipelineHistoryListRequestModel($request);
        $this->assertSame($request, $instance->getRequest());
    }
}
