<?php

namespace ConsulConfigManager\Tasks\Test\backup\UseCases\Pipeline\Get;

use function request;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Get\PipelineGetRequestModel;

/**
 * Class PipelineGetRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Pipeline\Get
 */
class PipelineGetRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new PipelineGetRequestModel($request, 123);
        $this->assertSame($request, $instance->getRequest());
    }
}
