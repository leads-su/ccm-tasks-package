<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\Action\Restore;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Action\Restore\ActionRestoreResponseModel;

/**
 * Class ActionRestoreResponseModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Action\Restore
 */
class ActionRestoreResponseModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $instance = new ActionRestoreResponseModel();
        $this->assertSame(null, $instance->getEntity());
    }
}
