<?php

namespace ConsulConfigManager\Tasks\Test\backup\UseCases\Action\Delete;

use function request;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Action\Delete\ActionDeleteRequestModel;

/**
 * Class ActionDeleteRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Action\Delete
 */
class ActionDeleteRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new ActionDeleteRequestModel($request, 123);
        $this->assertSame($request, $instance->getRequest());
    }
}
