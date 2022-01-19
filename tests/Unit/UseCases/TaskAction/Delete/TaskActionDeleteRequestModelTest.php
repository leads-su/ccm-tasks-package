<?php

namespace ConsulConfigManager\Tasks\Test\backup\UseCases\TaskAction\Delete;

use function request;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Delete\TaskActionDeleteRequestModel;

/**
 * Class TaskActionDeleteRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\TaskAction\Delete
 */
class TaskActionDeleteRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new TaskActionDeleteRequestModel($request, 1, 1);
        $this->assertSame($request, $instance->getRequest());
    }
}
