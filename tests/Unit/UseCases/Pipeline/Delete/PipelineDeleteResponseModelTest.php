<?php

namespace ConsulConfigManager\Tasks\Test\backup\UseCases\Pipeline\Delete;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Delete\PipelineDeleteResponseModel;

/**
 * Class PipelineDeleteResponseModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Pipeline\Delete
 */
class PipelineDeleteResponseModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $instance = new PipelineDeleteResponseModel();
        $this->assertSame(null, $instance->getEntity());
    }
}
