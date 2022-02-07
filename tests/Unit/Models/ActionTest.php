<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Models;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Models\Action;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\AggregateRoots\ActionAggregateRoot;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;

/**
 * Class ActionTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Models
 */
class ActionTest extends AbstractModelTest
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
    public function testShouldPassIfValidDataReturnedFromGetTypeMethod(array $data): void
    {
        $response = $this->model($data)->getType();
        $this->assertEquals(Arr::get($data, 'type'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetTypeMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setType(2);
        $this->assertEquals(2, $model->getType());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetCommandMethod(array $data): void
    {
        $response = $this->model($data)->getCommand();
        $this->assertEquals(Arr::get($data, 'command'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetCommandMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setCommand('python3');
        $this->assertEquals('python3', $model->getCommand());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetArgumentsMethod(array $data): void
    {
        $response = $this->model($data)->getArguments();
        $this->assertEquals(Arr::get($data, 'arguments'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetArgumentsMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setArguments(['test1', 'test2']);
        $this->assertEquals(['test1', 'test2'], $model->getArguments());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetWorkingDirectoryMethod(array $data): void
    {
        $response = $this->model($data)->getWorkingDirectory();
        $this->assertEquals(Arr::get($data, 'working_dir'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetWorkingDirectoryMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setWorkingDirectory('/home/example_user');
        $this->assertEquals('/home/example_user', $model->getWorkingDirectory());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetRunAsMethod(array $data): void
    {
        $response = $this->model($data)->getRunAs();
        $this->assertEquals(Arr::get($data, 'run_as'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetRunAsMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setRunAs('superuser');
        $this->assertEquals('superuser', $model->getRunAs());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromIsUsingSudoMethod(array $data): void
    {
        $response = $this->model($data)->isUsingSudo();
        $this->assertEquals(Arr::get($data, 'use_sudo'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromUseSudoMethod(array $data): void
    {
        $model = $this->model($data);
        $model->useSudo(true);
        $this->assertEquals(true, $model->isUsingSudo());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromIsFailingOnErrorMethod(array $data): void
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
    public function testShouldPassIfValidDataReturnedFromFailOnErrorMethod(array $data): void
    {
        $model = $this->model($data);
        $model->failOnError(false);
        $this->assertEquals(false, $model->isFailingOnError());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromUuidMethod(array $data): void
    {
        ActionAggregateRoot::retrieve(Arr::get($data, 'uuid'))
            ->createEntity(
                Arr::get($data, 'name'),
                Arr::get($data, 'description'),
                Arr::get($data, 'type'),
                Arr::get($data, 'command'),
                Arr::get($data, 'arguments'),
                Arr::get($data, 'working_dir'),
                Arr::get($data, 'run_as'),
                Arr::get($data, 'use_sudo'),
                Arr::get($data, 'fail_on_error'),
            )
            ->persist();

        $modelNoTrashed = Action::uuid(Arr::get($data, 'uuid'));
        $modelTrashed = Action::uuid(Arr::get($data, 'uuid'), true);
        $this->assertEquals($modelNoTrashed, $modelTrashed);
        $this->assertSame(Arr::get($data, 'id'), $modelNoTrashed->getID());
        $this->assertSame(Arr::get($data, 'uuid'), $modelNoTrashed->getUuid());
        $this->assertSame(Arr::get($data, 'name'), $modelNoTrashed->getName());
        $this->assertSame(Arr::get($data, 'description'), $modelNoTrashed->getDescription());
        $this->assertSame(Arr::get($data, 'type'), $modelNoTrashed->getType());
        $this->assertSame(Arr::get($data, 'command'), $modelNoTrashed->getCommand());
        $this->assertSame(Arr::get($data, 'arguments'), $modelNoTrashed->getArguments());
        $this->assertSame(Arr::get($data, 'working_dir'), $modelNoTrashed->getWorkingDirectory());
        $this->assertSame(Arr::get($data, 'run_as'), $modelNoTrashed->getRunAs());
        $this->assertSame(Arr::get($data, 'use_sudo'), $modelNoTrashed->isUsingSudo());
        $this->assertSame(Arr::get($data, 'fail_on_error'), $modelNoTrashed->isFailingOnError());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromHostsRelation(): void
    {
        $this->createCompletePipeline();
        $action = $this->repository()->findBy('uuid', self::$actionUUID);
        $this->assertCount(1, $action->hosts->toArray());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromServersAttribute(): void
    {
        $this->createCompletePipeline();
        $action = $this->repository()->findBy('uuid', self::$actionUUID);
        $this->assertCount(1, $action->servers);
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromServersExtendedAttribute(): void
    {
        $this->createCompletePipeline();
        $action = $this->repository()->findBy('uuid', self::$actionUUID);
        $this->assertCount(1, $action->serversExtended);
    }

    /**
     * Model data provider
     * @return \string[][][]
     */
    public function modelDataProvider(): array
    {
        return $this->actionModelDataProvider();
    }

    /**
     * Create model instance
     * @param array $data
     * @return ActionInterface
     */
    private function model(array $data): ActionInterface
    {
        return $this->actionModel($data);
    }

    /**
     * Create repository instance
     * @return ActionRepositoryInterface
     */
    private function repository(): ActionRepositoryInterface
    {
        return $this->actionRepository();
    }
}
