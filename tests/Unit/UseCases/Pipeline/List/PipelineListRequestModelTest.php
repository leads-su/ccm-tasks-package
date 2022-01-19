<?php

namespace ConsulConfigManager\Tasks\Test\backup\UseCases\Pipeline\List;

use function request;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Pipeline\List\PipelineListRequestModel;

/**
 * Class PipelineListRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Pipeline\List
 */
class PipelineListRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new PipelineListRequestModel($request);
        $this->assertSame($request, $instance->getRequest());
    }
}
