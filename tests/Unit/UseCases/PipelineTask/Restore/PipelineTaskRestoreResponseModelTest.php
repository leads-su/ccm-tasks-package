<?php

namespace ConsulConfigManager\Tasks\Test\backup\UseCases\PipelineTask\Restore;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Restore\PipelineTaskRestoreResponseModel;

/**
 * Class PipelineTaskRestoreResponseModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Task\Restore
 */
class PipelineTaskRestoreResponseModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $instance = new PipelineTaskRestoreResponseModel();
        $this->assertSame(null, $instance->getEntity());
    }
}
