<?php

namespace ConsulConfigManager\Tasks\Test\backup\UseCases\Action\Get;

use function request;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Action\Get\ActionGetRequestModel;

/**
 * Class ActionGetRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Action\Get
 */
class ActionGetRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new ActionGetRequestModel($request, 123);
        $this->assertSame($request, $instance->getRequest());
    }
}
