<?php

namespace ConsulConfigManager\Tasks\Test\backup\UseCases\PipelineTask\Update;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Update\PipelineTaskUpdateResponseModel;

/**
 * Class PipelineTaskUpdateResponseModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Task\Update
 */
class PipelineTaskUpdateResponseModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $instance = new PipelineTaskUpdateResponseModel();
        $this->assertSame(null, $instance->getPipelineInstance());
        $this->assertSame(null, $instance->getTaskInstance());
    }
}
