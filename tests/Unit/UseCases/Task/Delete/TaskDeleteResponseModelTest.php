<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\Task\Delete;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Task\Delete\TaskDeleteResponseModel;

/**
 * Class TaskDeleteResponseModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Task\Delete
 */
class TaskDeleteResponseModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $instance = new TaskDeleteResponseModel();
        $this->assertSame(null, $instance->getEntity());
    }
}
