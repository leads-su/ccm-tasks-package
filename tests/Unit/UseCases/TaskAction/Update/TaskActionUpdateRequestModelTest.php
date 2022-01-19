<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\TaskAction\Update;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\Http\Requests\TaskAction\TaskActionCreateUpdateRequest;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Update\TaskActionUpdateRequestModel;

/**
 * Class TaskActionUpdateRequestModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\TaskAction\Update
 */
class TaskActionUpdateRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfRequestIsReturned(): void
    {
        $request = new TaskActionCreateUpdateRequest();
        $instance = new TaskActionUpdateRequestModel($request, 1, 1);
        $this->assertSame($request, $instance->getRequest());
    }
}
