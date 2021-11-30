<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\Task\List;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Task\List\TaskListRequestModel;

/**
 * Class TaskListRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Task\List
 */
class TaskListRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new TaskListRequestModel($request);
        $this->assertSame($request, $instance->getRequest());
    }
}
