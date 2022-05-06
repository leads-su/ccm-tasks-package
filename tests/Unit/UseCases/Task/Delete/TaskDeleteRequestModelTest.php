<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\Task\Delete;

use function request;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Task\Delete\TaskDeleteRequestModel;

/**
 * Class TaskDeleteRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Task\Delete
 */
class TaskDeleteRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new TaskDeleteRequestModel($request, 123);
        $this->assertSame($request, $instance->getRequest());
    }
}
