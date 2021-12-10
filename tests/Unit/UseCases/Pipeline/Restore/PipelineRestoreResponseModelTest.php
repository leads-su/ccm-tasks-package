<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\Pipeline\Restore;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Restore\PipelineRestoreResponseModel;

/**
 * Class PipelineRestoreResponseModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Pipeline\Restore
 */
class PipelineRestoreResponseModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $instance = new PipelineRestoreResponseModel();
        $this->assertSame(null, $instance->getEntity());
    }
}
