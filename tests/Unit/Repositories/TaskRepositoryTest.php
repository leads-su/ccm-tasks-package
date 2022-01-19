<?php

namespace ConsulConfigManager\Tasks\Test\backup\Repositories;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Models\Task;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;

/**
 * Class TaskRepositoryTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Repositories
 */
class TaskRepositoryTest extends AbstractRepositoryTest
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

        Arr::set($data, 'type', 0);
        $entity = $this->repository()->update(
            $createdEntity->getID(),
            Arr::get($data, 'name'),
            Arr::get($data, 'description'),
            Arr::get($data, 'type'),
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
        $result = $this->repository()->findByMany(fields: ['id', 'uuid'], value: Arr::get($data, 'id'));
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
        $result = $this->repository()->findByMany(fields: ['id', 'uuid'], value: Arr::get($data, 'id'));
        $this->assertNotNull($result);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfNullIsReturnedFromFindByManyOrFailRequestWithId(array $data): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->repository()->findByManyOrFail(fields: ['id', 'uuid'], value: Arr::get($data, 'id'));
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfValueIsReturnedFromFindByManyOrFailRequestWithId(array $data): void
    {
        $this->createEntity($data);
        $response = $this->repository()->findByManyOrFail(fields: ['id', 'uuid'], value: Arr::get($data, 'id'));
        $this->assertSameReturned($response, $data);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfNullIsReturnedFromFindByManyOrFailRequestWithUuid(array $data): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->repository()->findByManyOrFail(fields: ['id', 'uuid'], value: Arr::get($data, 'uuid'));
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfValueIsReturnedFromFindByManyOrFailRequestWithUuid(array $data): void
    {
        $entity = $this->createEntity($data);
        Arr::set($data, 'uuid', $entity->getUuid());
        $response = $this->repository()->findByManyOrFail(fields: ['id', 'uuid'], value: Arr::get($data, 'uuid'));
        $this->assertSameReturned($response, $data);
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
     * Create new repository instance
     * @return TaskRepositoryInterface
     */
    private function repository(): TaskRepositoryInterface
    {
        return $this->app->make(TaskRepositoryInterface::class);
    }

    /**
     * Create new entity
     * @param array $data
     * @return TaskInterface
     */
    private function createEntity(array $data): TaskInterface
    {
        $entity = $this->repository()->create(
            Arr::get($data, 'name'),
            Arr::get($data, 'description'),
            Arr::get($data, 'type'),
        );
        $this->assertSameReturned($entity, $data);
        return $entity;
    }

    /**
     * Assert that data returned is the same as in array
     * @param Task $entity
     * @param array $data
     * @return void
     */
    private function assertSameReturned(TaskInterface $entity, array $data)
    {
        $this->assertInstanceOf(Task::class, $entity);
        $this->assertArrayHasKey('id', $entity);
        $this->assertArrayHasKey('uuid', $entity);
        $this->assertSame(Arr::get($data, 'name'), $entity->getName());
        $this->assertSame(Arr::get($data, 'description'), $entity->getDescription());
        $this->assertSame(Arr::get($data, 'type'), $entity->getType());
    }

    /**
     * Entity data provider
     * @return \array[][]
     */
    public function entityDataProvider(): array
    {
        return [
            'example_task'          =>  [
                'data'              =>  [
                    'id'            =>  1,
                    'uuid'          =>  '73f66d30-ad58-4641-8b25-05b245031b50',
                    'name'          =>  'Example Task',
                    'description'   =>  'Example Task Description',
                    'type'          =>  1,
                ],
            ],
        ];
    }
}
