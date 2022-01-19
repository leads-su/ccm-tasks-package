<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineTask\Delete;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Delete\PipelineTaskDeleteResponseModel;

/**
 * Class PipelineTaskDeleteResponseModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineTask\Delete
 */
class PipelineTaskDeleteResponseModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $instance = new PipelineTaskDeleteResponseModel();
        $this->assertSame(null, $instance->getEntity());
    }
}
