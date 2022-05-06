<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\TaskAction\Delete;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Delete\TaskActionDeleteResponseModel;

/**
 * Class TaskActionDeleteResponseModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\TaskAction\Delete
 */
class TaskActionDeleteResponseModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $instance = new TaskActionDeleteResponseModel();
        $this->assertSame(null, $instance->getEntity());
    }
}
