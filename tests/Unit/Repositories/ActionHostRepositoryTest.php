<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\ActionHostInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionHostRepositoryInterface;

/**
 * Class ActionHostRepositoryTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Repositories
 */
class ActionHostRepositoryTest extends AbstractRepositoryTest
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
    public function testShouldPassIfValidDataReturnedFromFindByActionRequest(array $data): void
    {
        $this->createEntity($data);

        $response = $this->repository()->findByAction(Arr::get($data, 'action_uuid'));
        $this->assertSameReturned(Arr::first($response), $data);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromFindByServerRequest(array $data): void
    {
        $this->createEntity($data);

        $response = $this->repository()->findByServer(Arr::get($data, 'server_uuid'));
        $this->assertSameReturned(Arr::first($response), $data);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromFindExactRequest(array $data): void
    {
        $this->createEntity($data);

        $response = $this->repository()->findExact(Arr::get($data, 'action_uuid'), Arr::get($data, 'server_uuid'));
        $this->assertSameReturned($response, $data);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfNullReturnedFromFindExactRequest(array $data): void
    {
        $response = $this->repository()->findExact(Arr::get($data, 'action_uuid'), Arr::get($data, 'server_uuid'));
        $this->assertNull($response);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromFindExactOrFailRequest(array $data): void
    {
        $this->createEntity($data);

        $response = $this->repository()->findExact(Arr::get($data, 'action_uuid'), Arr::get($data, 'server_uuid'));
        $this->assertSameReturned($response, $data);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfNullReturnedFromFindExactOrFailRequest(array $data): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->repository()->findExactOrFail(Arr::get($data, 'action_uuid'), Arr::get($data, 'server_uuid'));
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfTrueReturnedFromDeleteMethodForExistingEntity(array $data): void
    {
        $this->createEntity($data);
        $response = $this->repository()->delete(Arr::get($data, 'action_uuid'), Arr::get($data, 'server_uuid'));
        $this->assertTrue($response);
    }

    /**
     * @param array $data
     * @dataProvider entityDataProvider
     * @return void
     */
    public function testShouldPassIfExceptionThrownFromDeleteMethodForNonExistingEntity(array $data): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->repository()->delete(Arr::get($data, 'action_uuid'), Arr::get($data, 'server_uuid'));
    }


    /**
     * Create new repository instance
     * @return ActionHostRepositoryInterface
     */
    private function repository(): ActionHostRepositoryInterface
    {
        return $this->app->make(ActionHostRepositoryInterface::class);
    }

    /**
     * Create new entity
     * @param array $data
     * @return ActionHostInterface
     */
    private function createEntity(array $data): ActionHostInterface
    {
        $entity = $this->repository()->create(
            Arr::get($data, 'action_uuid'),
            Arr::get($data, 'server_uuid')
        );
        $this->assertSameReturned($entity, $data);
        return $entity;
    }

    /**
     * Assert that data returned is the same as in array
     * @param ActionHostInterface $entity
     * @param array $data
     * @return void
     */
    private function assertSameReturned(ActionHostInterface $entity, array $data)
    {
        $this->assertInstanceOf(ActionHostInterface::class, $entity);
        $this->assertSame(Arr::get($data, 'action_uuid'), $entity->getActionUuid());
        $this->assertSame(Arr::get($data, 'server_uuid'), $entity->getServiceUuid());
    }

    /**
     * Entity data provider
     * @return \array[][]
     */
    public function entityDataProvider(): array
    {
        return [
            'example_action_host_execution'     =>  [
                'data'                          =>  [
                    'action_uuid'               =>  'e5a50283-995e-4281-a178-a64fb0122d68',
                    'server_uuid'               =>  'acd000ad-58f5-439f-a1fd-7a208133e05e',
                ],
            ],
        ];
    }
}
