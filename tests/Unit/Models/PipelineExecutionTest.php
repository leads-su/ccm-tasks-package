<?php

namespace ConsulConfigManager\Tasks\Test\backup\Models;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Models\PipelineExecution;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionInterface;
use ConsulConfigManager\Tasks\AggregateRoots\PipelineExecutionAggregateRoot;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionRepositoryInterface;

/**
 * Class PipelineExecutionTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Models
 */
class PipelineExecutionTest extends AbstractModelTest
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
    public function testShouldPassIfValidDataReturnedFromGetStateMethod(array $data): void
    {
        $response = $this->model($data)->getState();
        $this->assertEquals(Arr::get($data, 'state'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetStateMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setState(2);
        $this->assertEquals(2, $model->getState());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromPipelineRelation(): void
    {
        $this->createCompletePipeline();
        $entity = $this->repository()->findBy('uuid', self::$pipelineExecutionUUID);
        $this->assertInstanceOf(PipelineInterface::class, $entity->pipeline);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromUuidMethod(array $data): void
    {
        PipelineExecutionAggregateRoot::retrieve(Arr::get($data, 'uuid'))
            ->createEntity(
                Arr::get($data, 'pipeline_uuid'),
                Arr::get($data, 'state'),
            )
            ->persist();

        $modelNoTrashed = PipelineExecution::uuid(Arr::get($data, 'uuid'));
        $modelTrashed = PipelineExecution::uuid(Arr::get($data, 'uuid'), true);
        $this->assertEquals($modelNoTrashed, $modelTrashed);
        $this->assertSame(Arr::get($data, 'id'), $modelNoTrashed->getID());
        $this->assertSame(Arr::get($data, 'uuid'), $modelNoTrashed->getUuid());
        $this->assertSame(Arr::get($data, 'pipeline_uuid'), $modelNoTrashed->getPipelineUuid());
        $this->assertSame(Arr::get($data, 'state'), $modelNoTrashed->getState());
    }

    /**
     * Model data provider
     * @return \string[][][]
     */
    public function modelDataProvider(): array
    {
        return $this->pipelineExecutionModelDataProvider();
    }

    /**
     * Create model instance
     * @param array $data
     * @return PipelineExecutionInterface
     */
    private function model(array $data): PipelineExecutionInterface
    {
        return $this->pipelineExecutionModel($data);
    }

    /**
     * Create repository instance
     * @return PipelineExecutionRepositoryInterface
     */
    private function repository(): PipelineExecutionRepositoryInterface
    {
        return $this->pipelineExecutionRepository();
    }
}
