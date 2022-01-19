<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\TaskAction\Get;

use function request;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Get\TaskActionGetRequestModel;

/**
 * Class TaskActionGetRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\TaskAction\Get
 */
class TaskActionGetRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new TaskActionGetRequestModel($request, 1, 1);
        $this->assertSame($request, $instance->getRequest());
    }
}
