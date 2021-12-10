<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Repositories;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Models\PipelineExecution;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionRepositoryInterface;

/**
 * Class PipelineExecutionRepositoryTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Repositories
 */
class PipelineExecutionRepositoryTest extends AbstractRepositoryTest
{
    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfCanCreateNewEntry(array $data): void
    {
        $this->createEntity($data);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfCanUpdateExistingEntry(array $data): void
    {
        $createdEntity = $this->createEntity($data);

        Arr::set($data, 'state', 2);
        $entity = $this->repository()->update(
            $createdEntity->getID(),
            Arr::get($data, 'pipeline_uuid'),
            Arr::get($data, 'state'),
        );
        $this->assertSameReturned($entity, $data);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromAllRequest(array $data): void
    {
        $this->createEntity($data);
        $response = $this->repository()->all();
        $this->assertSameReturned($response->first(), $data);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromFindRequest(array $data): void
    {
        $this->createEntity($data);

        $response = $this->repository()->find(Arr::get($data, 'id'));
        $this->assertSameReturned($response, $data);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromFindOrFailRequest(array $data): void
    {
        $this->createEntity($data);

        $response = $this->repository()->findOrFail(Arr::get($data, 'id'));
        $this->assertSameReturned($response, $data);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfExceptionIsThrownOnModelNotFoundFromFindOrFailRequest(array $data): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->repository()->findOrFail(Arr::get($data, 'id'));
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfTrueReturnedFromDeleteMethod(array $data): void
    {
        $this->createEntity($data);
        $response = $this->repository()->delete(Arr::get($data, 'id'));
        $this->assertTrue($response);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfFalseReturnedFromDeleteMethod(array $data): void
    {
        $response = $this->repository()->delete(Arr::get($data, 'id'));
        $this->assertFalse($response);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfTrueReturnedFromRestoreMethod(array $data): void
    {
        $this->createEntity($data);
        $response = $this->repository()->delete(Arr::get($data, 'id'));
        $this->assertTrue($response);
        $response = $this->repository()->restore(Arr::get($data, 'id'));
        $this->assertTrue($response);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfFalseReturnedFromRestoreMethod(array $data): void
    {
        $response = $this->repository()->restore(Arr::get($data, 'id'));
        $this->assertFalse($response);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfTrueReturnedFromForceDeleteMethod(array $data): void
    {
        $this->createEntity($data);
        $response = $this->repository()->forceDelete(Arr::get($data, 'id'));
        $this->assertTrue($response);
    }

    /**
     * Create new repository instance
     * @return PipelineExecutionRepositoryInterface
     */
    private function repository(): PipelineExecutionRepositoryInterface
    {
        return $this->app->make(PipelineExecutionRepositoryInterface::class);
    }

    /**
     * Create new entity
     * @param array $data
     * @return PipelineExecutionInterface
     */
    private function createEntity(array $data): PipelineExecutionInterface
    {
        $entity = $this->repository()->create(
            Arr::get($data, 'pipeline_uuid'),
            Arr::get($data, 'state'),
        );
        $this->assertSameReturned($entity, $data);
        return $entity;
    }

    /**
     * Assert that data returned is the same as in array
     * @param PipelineExecutionInterface $entity
     * @param array $data
     * @return void
     */
    private function assertSameReturned(PipelineExecutionInterface $entity, array $data)
    {
        $this->assertInstanceOf(PipelineExecution::class, $entity);
        $this->assertArrayHasKey('id', $entity);
        $this->assertArrayHasKey('uuid', $entity);
        $this->assertSame(Arr::get($data, 'pipeline_uuid'), $entity->getPipelineUuid());
        $this->assertSame(Arr::get($data, 'state'), $entity->getState());
    }

    /**
     * Entity data provider
     * @return \array[][]
     */
    public function entityDataProvider(): array
    {
        return [
            'example_pipeline_execution'    =>  [
                'data'                      =>  [
                    'id'                    =>  1,
                    'uuid'                  =>  '73f66d30-ad58-4641-8b25-05b245031b50',
                    'pipeline_uuid'         =>  'eb6e9048-c9d7-4729-a065-2696268a6b5b',
                    'state'                 =>  1,
                ],
            ],
        ];
    }
}
