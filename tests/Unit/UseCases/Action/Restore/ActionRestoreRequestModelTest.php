<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\Action\Restore;

use function request;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Action\Restore\ActionRestoreRequestModel;

/**
 * Class ActionRestoreRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Action\Restore
 */
class ActionRestoreRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new ActionRestoreRequestModel($request, 123);
        $this->assertSame($request, $instance->getRequest());
    }
}
