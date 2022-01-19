<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\Task\Get;

use function request;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Task\Get\TaskGetRequestModel;

/**
 * Class TaskGetRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Task\Get
 */
class TaskGetRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new TaskGetRequestModel($request, 123);
        $this->assertSame($request, $instance->getRequest());
    }
}
