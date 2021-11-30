<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\Action\List;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Action\List\ActionListRequestModel;

/**
 * Class ActionListRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Action\List
 */
class ActionListRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new ActionListRequestModel($request);
        $this->assertSame($request, $instance->getRequest());
    }
}
