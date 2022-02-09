<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\ActionExecution\Get;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\ActionExecution\Get\ActionExecutionGetRequestModel;

/**
 * Class ActionExecutionGetRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\ActionExecution\Get
 */
class ActionExecutionGetRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new ActionExecutionGetRequestModel($request, 'identifier', 123);
        $this->assertSame($request, $instance->getRequest());
    }
}
