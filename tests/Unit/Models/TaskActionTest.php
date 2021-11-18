<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Models;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Interfaces\TaskActionInterface;

/**
 * Class TaskActionTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Models
 */
class TaskActionTest extends AbstractModelTest
{
    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetActionUuidMethod(array $data): void
    {
        $response = $this->model($data)->getActionUuid();
        $this->assertEquals(Arr::get($data, 'action_uuid'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetActionUuidMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setActionUuid('0e013a2b-03f6-404d-b8f6-fd186191c145');
        $this->assertEquals('0e013a2b-03f6-404d-b8f6-fd186191c145', $model->getActionUuid());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetTaskUuidMethod(array $data): void
    {
        $response = $this->model($data)->getTaskUuid();
        $this->assertEquals(Arr::get($data, 'task_uuid'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetTaskUuidMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setTaskUuid('0e013a2b-03f6-404d-b8f6-fd186191c145');
        $this->assertEquals('0e013a2b-03f6-404d-b8f6-fd186191c145', $model->getTaskUuid());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetOrderMethod(array $data): void
    {
        $response = $this->model($data)->getOrder();
        $this->assertEquals(Arr::get($data, 'order'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetOrderMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setOrder(10);
        $this->assertEquals(10, $model->getOrder());
    }

    /**
     * Model data provider
     * @return \string[][][]
     */
    public function modelDataProvider(): array
    {
        return $this->taskActionModelDataProvider();
    }

    /**
     * Create model instance
     * @param array $data
     * @return TaskActionInterface
     */
    private function model(array $data): TaskActionInterface
    {
        return $this->taskActionModel($data);
    }
}
