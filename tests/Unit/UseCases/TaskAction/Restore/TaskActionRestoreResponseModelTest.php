<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\TaskAction\Restore;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Restore\TaskActionRestoreResponseModel;

/**
 * Class TaskActionRestoreResponseModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Task\Restore
 */
class TaskActionRestoreResponseModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $instance = new TaskActionRestoreResponseModel();
        $this->assertSame(null, $instance->getEntity());
    }
}
