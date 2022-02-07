<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\Service\List;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Service\List\ServiceListRequestModel;

/**
 * Class ServiceListRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Service\List
 */
class ServiceListRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = request();
        $instance = new ServiceListRequestModel($request, );
        $this->assertSame($request, $instance->getRequest());
    }
}
