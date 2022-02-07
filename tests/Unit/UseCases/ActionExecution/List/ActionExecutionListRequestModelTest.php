<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\ActionExecution\List;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\ActionExecution\List\ActionExecutionListRequestModel;

/**
 * Class ActionExecutionListRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\ActionExecution\List
 */
class ActionExecutionListRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new ActionExecutionListRequestModel($request, 'identifier');
        $this->assertSame($request, $instance->getRequest());
    }
}
