<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\Pipeline\Delete;

use function request;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Delete\PipelineDeleteRequestModel;

/**
 * Class PipelineDeleteRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Pipeline\Delete
 */
class PipelineDeleteRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new PipelineDeleteRequestModel($request, 123);
        $this->assertSame($request, $instance->getRequest());
    }
}
