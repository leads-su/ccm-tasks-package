<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineTask\Restore;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Restore\PipelineTaskRestoreRequestModel;

/**
 * Class PipelineTaskRestoreRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineTask\Restore
 */
class PipelineTaskRestoreRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new PipelineTaskRestoreRequestModel($request, 1, 1);
        $this->assertSame($request, $instance->getRequest());
    }
}
