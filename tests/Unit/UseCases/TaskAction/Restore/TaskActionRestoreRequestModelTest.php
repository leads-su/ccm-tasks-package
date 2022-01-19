<?php

namespace ConsulConfigManager\Tasks\Test\backup\UseCases\TaskAction\Restore;

use function request;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Restore\TaskActionRestoreRequestModel;

/**
 * Class TaskActionRestoreRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\TaskAction\Restore
 */
class TaskActionRestoreRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new TaskActionRestoreRequestModel($request, 1, 1);
        $this->assertSame($request, $instance->getRequest());
    }
}
