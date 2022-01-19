<?php

namespace ConsulConfigManager\Tasks\Test\backup\Repositories;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Models\Action;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;

/**
 * Class ActionRepositoryTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Repositories
 */
class ActionRepositoryTest extends AbstractRepositoryTest
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
            Arr::get($data, 'command'),
            Arr::get($data, 'arguments'),
            Arr::get($data, 'working_dir'),
            Arr::get($data, 'run_as'),
            Arr::get($data, 'use_sudo'),
            Arr::get($data, 'fail_on_error'),
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
        $this->assertTrue($response, 'Failed to validate that action was deleted');
        $response = $this->repository()->restore(Arr::get($data, 'id'));
        $this->assertTrue($response, 'Failed to validate that action was restored');
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
     * @return ActionRepositoryInterface
     */
    private function repository(): ActionRepositoryInterface
    {
        return $this->app->make(ActionRepositoryInterface::class);
    }

    /**
     * Create new entity
     * @param array $data
     * @return ActionInterface
     */
    private function createEntity(array $data): ActionInterface
    {
        $entity = $this->repository()->create(
            Arr::get($data, 'name'),
            Arr::get($data, 'description'),
            Arr::get($data, 'type'),
            Arr::get($data, 'command'),
            Arr::get($data, 'arguments'),
            Arr::get($data, 'working_dir'),
            Arr::get($data, 'run_as'),
            Arr::get($data, 'use_sudo'),
            Arr::get($data, 'fail_on_error'),
        );
        $this->assertSameReturned($entity, $data);
        return $entity;
    }

    /**
     * Assert that data returned is the same as in array
     * @param ActionInterface $entity
     * @param array $data
     * @return void
     */
    private function assertSameReturned(ActionInterface $entity, array $data)
    {
        $this->assertInstanceOf(Action::class, $entity);
        $this->assertArrayHasKey('id', $entity);
        $this->assertArrayHasKey('uuid', $entity);
        $this->assertSame(Arr::get($data, 'name'), $entity->getName());
        $this->assertSame(Arr::get($data, 'description'), $entity->getDescription());
        $this->assertSame(Arr::get($data, 'type'), $entity->getType());
        $this->assertSame(Arr::get($data, 'command'), $entity->getCommand());
        $this->assertSame(Arr::get($data, 'arguments'), $entity->getArguments());
        $this->assertSame(Arr::get($data, 'working_dir'), $entity->getWorkingDirectory());
        $this->assertSame(Arr::get($data, 'run_as'), $entity->getRunAs());
        $this->assertSame(Arr::get($data, 'use_sudo'), $entity->isUsingSudo());
        $this->assertSame(Arr::get($data, 'fail_on_error'), $entity->isFailingOnError());
    }

    /**
     * Entity data provider
     * @return \array[][]
     */
    public function entityDataProvider(): array
    {
        return [
            'example_action'        =>  [
                'data'              =>  [
                    'id'            =>  1,
                    'uuid'          =>  '73f66d30-ad58-4641-8b25-05b245031b50',
                    'name'          =>  'Example Action',
                    'description'   =>  'Example Action Description',
                    'type'          =>  1,
                    'command'       =>  'php',
                    'arguments'     =>  ['test.php'],
                    'working_dir'   =>  '/home/cabinet',
                    'run_as'        =>  'cabinet',
                    'use_sudo'      =>  false,
                    'fail_on_error' =>  true,
                ],
            ],
        ];
    }
}
