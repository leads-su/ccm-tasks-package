<?php

namespace ConsulConfigManager\Tasks\Test\backup\Repositories;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Models\TaskExecution;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionRepositoryInterface;

/**
 * Class TaskExecutionRepositoryTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Repositories
 */
class TaskExecutionRepositoryTest extends AbstractRepositoryTest
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
            $createdEntity->getTaskUuid(),
            $createdEntity->getPipelineUuid(),
            $createdEntity->getPipelineExecutionUuid(),
            Arr::get($data, 'state'),
        );
        $this->assertSameReturned($entity, $data);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfCanUpdateByIdExistingEntry(array $data): void
    {
        $createdEntity = $this->createEntity($data);

        Arr::set($data, 'state', 2);
        $entity = $this->repository()->updateById(
            $createdEntity->getID(),
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
    public function testShouldPassIfNullIsReturnedFromFindByManyRequest(array $data): void
    {
        $result = $this->repository()->findByMany(fields: ['id'], value: Arr::get($data, 'id'));
        $this->assertNull($result);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfValueIsReturnedFromFindByManyRequest(array $data): void
    {
        $this->createEntity($data);
        $result = $this->repository()->findByMany(fields: ['id'], value: Arr::get($data, 'id'));
        $this->assertNotNull($result);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromFindByManyRequest(array $data): void
    {
        $this->createEntity($data);

        $response = $this->repository()->findByMany(['id', 'uuid'], Arr::get($data, 'id'));
        $this->assertSameReturned($response, $data);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromFindByManyOrFailRequest(array $data): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->repository()->findByManyOrFail(['id', 'uuid'], Arr::get($data, 'id'));
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
    public function testShouldPassIfTrueReturnedFromForceDeleteMethod(array $data): void
    {
        $this->createEntity($data);
        $response = $this->repository()->forceDelete(Arr::get($data, 'id'));
        $this->assertTrue($response);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfFalseReturnedFromForceDeleteMethod(array $data): void
    {
        $response = $this->repository()->forceDelete(Arr::get($data, 'id'));
        $this->assertFalse($response);
    }

    /**
     * Create new repository instance
     * @return TaskExecutionRepositoryInterface
     */
    private function repository(): TaskExecutionRepositoryInterface
    {
        return $this->app->make(TaskExecutionRepositoryInterface::class);
    }

    /**
     * Create new entity
     * @param array $data
     * @return TaskExecutionInterface
     */
    private function createEntity(array $data): TaskExecutionInterface
    {
        $entity = $this->repository()->create(
            Arr::get($data, 'task_uuid'),
            Arr::get($data, 'pipeline_uuid'),
            Arr::get($data, 'pipeline_execution_uuid'),
            Arr::get($data, 'state'),
        );
        $this->assertSameReturned($entity, $data);
        return $entity;
    }

    /**
     * Assert that data returned is the same as in array
     * @param TaskExecutionInterface $entity
     * @param array $data
     * @return void
     */
    private function assertSameReturned(TaskExecutionInterface $entity, array $data)
    {
        $this->assertInstanceOf(TaskExecution::class, $entity);
        $this->assertArrayHasKey('id', $entity);
        $this->assertSame(Arr::get($data, 'task_uuid'), $entity->getTaskUuid());
        $this->assertSame(Arr::get($data, 'pipeline_uuid'), $entity->getPipelineUuid());
        $this->assertSame(Arr::get($data, 'pipeline_execution_uuid'), $entity->getPipelineExecutionUuid());
        $this->assertSame(Arr::get($data, 'state'), $entity->getState());
    }

    /**
     * Entity data provider
     * @return \array[][]
     */
    public function entityDataProvider(): array
    {
        return [
            'example_task_execution'            =>  [
                'data'                          =>  [
                    'id'                        =>  1,
                    'task_uuid'                 =>  '73f66d30-ad58-4641-8b25-05b245031b50',
                    'pipeline_uuid'             =>  'eb6e9048-c9d7-4729-a065-2696268a6b5b',
                    'pipeline_execution_uuid'   =>  '452c8792-a983-4ee2-a999-850d4de3f942',
                    'state'                     =>  1,
                ],
            ],
        ];
    }
}
