<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\Pipeline\Restore;

use function request;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Restore\PipelineRestoreRequestModel;

/**
 * Class PipelineRestoreRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Pipeline\Restore
 */
class PipelineRestoreRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new PipelineRestoreRequestModel($request, 123);
        $this->assertSame($request, $instance->getRequest());
    }
}
