<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\Task\Get;

use ConsulConfigManager\Tasks\Models\Task;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Task\Get\TaskGetResponseModel;

/**
 * Class TaskGetResponseModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Task\Get
 */
class TaskGetResponseModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedWithPipelinePassedIsReturned(): void
    {
        $instance = new TaskGetResponseModel(new Task());
        $this->assertSame([], $instance->getEntity());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedWithArrayPassedIsReturned(): void
    {
        $instance = new TaskGetResponseModel([]);
        $this->assertSame([], $instance->getEntity());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedWithNullPassedIsReturned(): void
    {
        $instance = new TaskGetResponseModel(null);
        $this->assertSame([], $instance->getEntity());
    }
}
