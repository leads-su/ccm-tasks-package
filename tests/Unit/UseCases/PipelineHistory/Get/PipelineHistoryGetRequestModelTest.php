<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineHistory\Get;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\PipelineHistory\Get\PipelineHistoryGetRequestModel;

/**
 * Class PipelineHistoryGetRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineHistory\Get
 */
class PipelineHistoryGetRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new PipelineHistoryGetRequestModel($request, 'identifier');
        $this->assertSame($request, $instance->getRequest());
    }
}
