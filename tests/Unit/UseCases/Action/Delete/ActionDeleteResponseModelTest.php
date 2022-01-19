<?php

namespace ConsulConfigManager\Tasks\Test\backup\UseCases\Action\Delete;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\Action\Delete\ActionDeleteResponseModel;

/**
 * Class ActionDeleteResponseModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Action\Delete
 */
class ActionDeleteResponseModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $instance = new ActionDeleteResponseModel();
        $this->assertSame(null, $instance->getEntity());
    }
}
