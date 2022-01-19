<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Models;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Models\PipelineTask;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskInterface;
use ConsulConfigManager\Tasks\AggregateRoots\PipelineTaskAggregateRoot;

/**
 * Class PipelineTaskTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Models
 */
class PipelineTaskTest extends AbstractModelTest
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
    public function testShouldPassIfValidDataReturnedFromGetPipelineUuidMethod(array $data): void
    {
        $response = $this->model($data)->getPipelineUuid();
        $this->assertEquals(Arr::get($data, 'pipeline_uuid'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetPipelineUuidMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setPipelineUuid('0e013a2b-03f6-404d-b8f6-fd186191c145');
        $this->assertEquals('0e013a2b-03f6-404d-b8f6-fd186191c145', $model->getPipelineUuid());
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
    public function testShouldPassIfValidDataReturnedFromPipelineRelation(): void
    {
        $this->createCompletePipeline();
        $entity = PipelineTask::where('pipeline_uuid', '=', self::$pipelineUUID)->first();
        $this->assertInstanceOf(PipelineInterface::class, $entity->pipeline);
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromTaskRelation(): void
    {
        $this->createCompletePipeline();
        $entity = PipelineTask::where('pipeline_uuid', '=', self::$pipelineUUID)->first();
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
        PipelineTaskAggregateRoot::retrieve(Arr::get($data, 'uuid'))
            ->createEntity(
                Arr::get($data, 'pipeline_uuid'),
                Arr::get($data, 'task_uuid'),
                Arr::get($data, 'order'),
            )
            ->persist();

        $modelNoTrashed = PipelineTask::uuid(Arr::get($data, 'uuid'));
        $modelTrashed = PipelineTask::uuid(Arr::get($data, 'uuid'), true);
        $this->assertEquals($modelNoTrashed, $modelTrashed);
        $this->assertSame(Arr::get($data, 'uuid'), $modelNoTrashed->getUuid());
        $this->assertSame(Arr::get($data, 'pipeline_uuid'), $modelNoTrashed->getPipelineUuid());
        $this->assertSame(Arr::get($data, 'task_uuid'), $modelNoTrashed->getTaskUuid());
        $this->assertSame(Arr::get($data, 'order'), $modelNoTrashed->getOrder());
    }

    /**
     * Model data provider
     * @return \string[][][]
     */
    public function modelDataProvider(): array
    {
        return $this->pipelineTaskModelDataProvider();
    }

    /**
     * Create model instance
     * @param array $data
     * @return PipelineTaskInterface
     */
    private function model(array $data): PipelineTaskInterface
    {
        return $this->pipelineTaskModel($data);
    }
}
