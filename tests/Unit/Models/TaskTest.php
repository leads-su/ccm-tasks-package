<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Models;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Models\Task;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\AggregateRoots\TaskAggregateRoot;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;

/**
 * Class TaskTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Models
 */
class TaskTest extends AbstractModelTest
{
    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetIdMethod(array $data): void
    {
        $response = $this->model($data)->getID();
        $this->assertEquals(Arr::get($data, 'id'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetIdMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setID(2);
        $this->assertEquals(2, $model->getID());
    }

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
    public function testShouldPassIfValidDataReturnedFromGetNameMethod(array $data): void
    {
        $response = $this->model($data)->getName();
        $this->assertEquals(Arr::get($data, 'name'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetNameMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setName('New Name');
        $this->assertEquals('New Name', $model->getName());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetDescriptionMethod(array $data): void
    {
        $response = $this->model($data)->getDescription();
        $this->assertEquals(Arr::get($data, 'description'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetDescriptionMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setDescription('New Description');
        $this->assertEquals('New Description', $model->getDescription());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromUuidMethod(array $data): void
    {
        TaskAggregateRoot::retrieve(Arr::get($data, 'uuid'))
            ->createEntity(
                Arr::get($data, 'name'),
                Arr::get($data, 'description'),
            )
            ->persist();

        $modelNoTrashed = Task::uuid(Arr::get($data, 'uuid'));
        $modelTrashed = Task::uuid(Arr::get($data, 'uuid'), true);
        $this->assertEquals($modelNoTrashed, $modelTrashed);
        $this->assertSame(Arr::get($data, 'id'), $modelNoTrashed->getID());
        $this->assertSame(Arr::get($data, 'uuid'), $modelNoTrashed->getUuid());
        $this->assertSame(Arr::get($data, 'name'), $modelNoTrashed->getName());
        $this->assertSame(Arr::get($data, 'description'), $modelNoTrashed->getDescription());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromFailOnErrorMethod(array $data): void
    {
        $response = $this->model($data)->isFailingOnError();
        $this->assertEquals(Arr::get($data, 'fail_on_error'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromIsFailingOnErrorMethod(array $data): void
    {
        $model = $this->model($data);
        $model->failOnError(true);
        $this->assertEquals(true, $model->isFailingOnError());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromActionsRelation(): void
    {
        $this->createCompletePipeline();
        $task = $this->repository()->findBy('uuid', self::$taskUUID);
        $this->assertCount(1, $task->actions->toArray());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromActionsListAttribute(): void
    {
        $this->createCompletePipeline();
        $task = $this->repository()->findBy('uuid', self::$taskUUID);
        $this->assertCount(1, $task->actionsList);
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromActionsListExtendedAttribute(): void
    {
        $this->createCompletePipeline();
        $task = $this->repository()->findBy('uuid', self::$taskUUID);
        $this->assertCount(1, $task->actionsListExtended);
    }

    /**
     * Model data provider
     * @return \string[][][]
     */
    public function modelDataProvider(): array
    {
        return $this->taskModelDataProvider();
    }

    /**
     * Create model instance
     * @param array $data
     * @return TaskInterface
     */
    private function model(array $data): TaskInterface
    {
        return $this->taskModel($data);
    }

    /**
     * Create repository instance
     * @return TaskRepositoryInterface
     */
    private function repository(): TaskRepositoryInterface
    {
        return $this->taskRepository();
    }
}
