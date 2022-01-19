<?php

namespace ConsulConfigManager\Tasks\Test\backup\Models;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Models\Pipeline;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Tasks\AggregateRoots\PipelineAggregateRoot;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;

/**
 * Class PipelineTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Models
 */
class PipelineTest extends AbstractModelTest
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
        PipelineAggregateRoot::retrieve(Arr::get($data, 'uuid'))
            ->createEntity(
                Arr::get($data, 'name'),
                Arr::get($data, 'description')
            )
            ->persist();

        $modelNoTrashed = Pipeline::uuid(Arr::get($data, 'uuid'));
        $modelTrashed = Pipeline::uuid(Arr::get($data, 'uuid'), true);
        $this->assertEquals($modelNoTrashed, $modelTrashed);
        $this->assertSame(Arr::get($data, 'id'), $modelNoTrashed->getID());
        $this->assertSame(Arr::get($data, 'uuid'), $modelNoTrashed->getUuid());
        $this->assertSame(Arr::get($data, 'name'), $modelNoTrashed->getName());
        $this->assertSame(Arr::get($data, 'description'), $modelNoTrashed->getDescription());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromTasksRelation(): void
    {
        $this->createCompletePipeline();
        $action = $this->repository()->findBy('uuid', self::$pipelineUUID);
        $this->assertCount(1, $action->tasks->toArray());
    }

    /**
     * Model data provider
     * @return \string[][][]
     */
    public function modelDataProvider(): array
    {
        return $this->pipelineModelDataProvider();
    }

    /**
     * Create model instance
     * @param array $data
     * @return PipelineInterface
     */
    private function model(array $data): PipelineInterface
    {
        return $this->pipelineModel($data);
    }

    /**
     * Create repository instance
     * @return PipelineRepositoryInterface
     */
    private function repository(): PipelineRepositoryInterface
    {
        return $this->pipelineRepository();
    }
}
