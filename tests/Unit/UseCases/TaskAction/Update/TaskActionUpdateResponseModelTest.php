<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\TaskAction\Update;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Update\TaskActionUpdateResponseModel;

/**
 * Class TaskActionUpdateResponseModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Task\Update
 */
class TaskActionUpdateResponseModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $instance = new TaskActionUpdateResponseModel();
        $this->assertSame(null, $instance->getAction());
        $this->assertSame(null, $instance->getTask());
    }
}
