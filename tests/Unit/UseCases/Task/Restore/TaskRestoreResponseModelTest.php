<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\Task\Restore;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Task\Restore\TaskRestoreResponseModel;

/**
 * Class TaskRestoreResponseModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Task\Restore
 */
class TaskRestoreResponseModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $instance = new TaskRestoreResponseModel();
        $this->assertSame(null, $instance->getEntity());
    }
}
