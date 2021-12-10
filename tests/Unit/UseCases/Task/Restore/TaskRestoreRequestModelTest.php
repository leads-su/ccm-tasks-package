<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\Task\Restore;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Task\Restore\TaskRestoreRequestModel;

/**
 * Class TaskRestoreRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Task\Restore
 */
class TaskRestoreRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new TaskRestoreRequestModel($request, 123);
        $this->assertSame($request, $instance->getRequest());
    }
}
