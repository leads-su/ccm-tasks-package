<?php

namespace ConsulConfigManager\Tasks\Test\backup\Models;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Models\TaskAction;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskActionInterface;
use ConsulConfigManager\Tasks\AggregateRoots\TaskActionAggregateRoot;

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
    public function testShouldPassIfValidDataReturnedFromGetUuidMethod(array $data): void
    {
        $response = $this->model($data)->getUuid();
        $this->assertEquals(Arr::get($data, 'uuid'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetUuidMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setUuid('0e013a2b-03f6-404d-b8f6-fd186191c145');
        $this->assertEquals('0e013a2b-03f6-404d-b8f6-fd186191c145', $model->getUuid());
    }

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
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromActionRelation(): void
    {
        $this->createCompletePipeline();
        $entity = TaskAction::where('task_uuid', '=', self::$taskUUID)->first();
        $this->assertInstanceOf(ActionInterface::class, $entity->action);
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromTaskRelation(): void
    {
        $this->createCompletePipeline();
        $entity = TaskAction::where('task_uuid', '=', self::$taskUUID)->first();
        $this->assertInstanceOf(TaskInterface::class, $entity->task);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromUuidMethod(array $data): void
    {
        TaskActionAggregateRoot::retrieve(Arr::get($data, 'uuid'))
            ->createEntity(
                Arr::get($data, 'task_uuid'),
                Arr::get($data, 'action_uuid'),
                Arr::get($data, 'order'),
            )
            ->persist();

        $modelNoTrashed = TaskAction::uuid(Arr::get($data, 'uuid'));
        $modelTrashed = TaskAction::uuid(Arr::get($data, 'uuid'), true);
        $this->assertEquals($modelNoTrashed, $modelTrashed);
        $this->assertSame(Arr::get($data, 'uuid'), $modelNoTrashed->getUuid());
        $this->assertSame(Arr::get($data, 'task_uuid'), $modelNoTrashed->getTaskUuid());
        $this->assertSame(Arr::get($data, 'action_uuid'), $modelNoTrashed->getActionUuid());
        $this->assertSame(Arr::get($data, 'order'), $modelNoTrashed->getOrder());
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
