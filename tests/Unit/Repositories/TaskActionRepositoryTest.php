<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Repositories;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Models\Task;
use ConsulConfigManager\Tasks\Models\Action;
use ConsulConfigManager\Tasks\Models\TaskAction;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskActionInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskActionRepositoryInterface;

/**
 * Class TaskActionRepositoryTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Repositories
 */
class TaskActionRepositoryTest extends AbstractRepositoryTest
{
    /**
     * @param array $data
     * @return void
     * @dataProvider entityDataProvider
     * @throws BindingResolutionException
     */
    public function testShouldPassIfCanCreateNewEntity(array $data): void
    {
        $this->createEntity($data);
    }

    /**
     * @param array $data
     * @return void
     * @throws BindingResolutionException
     * @dataProvider entityDataProvider
     */
    public function testShouldPassIfCanUpdateExistingEntity(array $data): void
    {
        $data = $this->hydrateData($data);
        $this->createEntity($data);
        $this->repository()->update(Arr::get($data, 'task_uuid'), Arr::get($data, 'action_uuid'), 2);
        $entity = $this->repository()->get(Arr::get($data, 'task_uuid'), Arr::get($data, 'action_uuid'));
        $this->assertSame(2, $entity->getOrder());
    }

    /**
     * @param array $data
     * @return void
     * @throws BindingResolutionException
     * @dataProvider entityDataProvider
     */
    public function testShouldPassIfCanDeleteExistingEntity(array $data): void
    {
        $data = $this->hydrateData($data);
        $this->createEntity($data);
        $result = $this->repository()->delete(Arr::get($data, 'task_uuid'), Arr::get($data, 'action_uuid'));
        $this->assertTrue($result);
    }

    /**
     * @param array $data
     * @return void
     * @throws BindingResolutionException
     * @dataProvider entityDataProvider
     */
    public function testShouldPassIfCanForceDeleteExistingEntity(array $data): void
    {
        $data = $this->hydrateData($data);
        $this->createEntity($data);
        $result = $this->repository()->forceDelete(Arr::get($data, 'task_uuid'), Arr::get($data, 'action_uuid'));
        $this->assertTrue($result);
    }

    /**
     * @param array $data
     * @return void
     * @throws BindingResolutionException
     * @dataProvider entityDataProvider
     */
    public function testShouldPassIfTaskAndActionAreBoundAndTaskActionUuidReturned(array $data): void
    {
        $data = $this->hydrateData($data);
        $this->createEntity($data);
        $result = $this->repository()->taskActionUUID(Arr::get($data, 'task_uuid'), Arr::get($data, 'action_uuid'));
        $this->assertNotEquals(0, strlen($result));
    }

    /**
     * @param array $data
     * @return void
     * @throws BindingResolutionException
     * @dataProvider entityDataProvider
     */
    public function testShouldPassIfTaskAndActionAreNotBoundAndEmptyStringReturned(array $data): void
    {
        $data = $this->hydrateData($data);
        $this->createEntity($data);
        $result = $this->repository()->taskActionUUID(Arr::get($data, 'task_uuid'), $this->createActionEntity()->getUuid());
        $this->assertEquals(0, strlen($result));
    }

    /**
     * @param array $data
     * @return void
     * @throws BindingResolutionException
     * @dataProvider entityDataProvider
     */
    public function testShouldPassIfTaskAndActionAreBoundAndTrueReturned(array $data): void
    {
        $data = $this->hydrateData($data);
        $this->createEntity($data);
        $result = $this->repository()->isBound(Arr::get($data, 'task_uuid'), Arr::get($data, 'action_uuid'));
        $this->assertTrue($result);
    }

    /**
     * @param array $data
     * @return void
     * @throws BindingResolutionException
     * @dataProvider entityDataProvider
     */
    public function testShouldPassIfTaskAndActionAreNotBoundAndFalseReturned(array $data): void
    {
        $data = $this->hydrateData($data);
        $this->createEntity($data);
        $result = $this->repository()->isBound(Arr::get($data, 'task_uuid'), $this->createActionEntity()->getUuid());
        $this->assertFalse($result);
    }

    /**
     * Create new repository instance
     * @return TaskActionRepositoryInterface
     */
    private function repository(): TaskActionRepositoryInterface
    {
        return $this->app->make(TaskActionRepositoryInterface::class);
    }

    /**
     * Create new task repository instance
     * @return TaskRepositoryInterface
     */
    private function taskRepository(): TaskRepositoryInterface
    {
        return $this->app->make(TaskRepositoryInterface::class);
    }

    /**
     * Create new action repository instance
     * @return ActionRepositoryInterface
     */
    private function actionRepository(): ActionRepositoryInterface
    {
        return $this->app->make(ActionRepositoryInterface::class);
    }

    /**
     * Assert that data returned is the same as in array
     * @param TaskActionInterface $entity
     * @param array $data
     * @return void
     */
    private function assertSameReturned(TaskActionInterface $entity, array $data)
    {
        $this->assertInstanceOf(TaskAction::class, $entity);
        $this->assertArrayHasKey('uuid', $entity);
        $this->assertSame(Arr::get($data, 'task_uuid'), $entity->getTaskUuid());
        $this->assertSame(Arr::get($data, 'action_uuid'), $entity->getActionUuid());
        $this->assertSame(Arr::get($data, 'order'), $entity->getOrder());
    }

    /**
     * Assert that data returned is the same as in array
     * @param Task $entity
     * @param array $data
     * @return void
     */
    private function assertSameTaskReturned(TaskInterface $entity, array $data)
    {
        $this->assertInstanceOf(Task::class, $entity);
        $this->assertArrayHasKey('id', $entity);
        $this->assertArrayHasKey('uuid', $entity);
        $this->assertSame(Arr::get($data, 'name'), $entity->getName());
        $this->assertSame(Arr::get($data, 'description'), $entity->getDescription());
    }

    /**
     * Assert that data returned is the same as in array
     * @param ActionInterface $entity
     * @param array $data
     * @return void
     */
    private function assertSameActionReturned(ActionInterface $entity, array $data)
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
     * Create new entity
     * @param array $data
     * @return TaskActionInterface
     * @throws BindingResolutionException
     */
    private function createEntity(array $data): TaskActionInterface
    {
        if (Arr::get($data, 'task_uuid') === '' || Arr::get($data, 'action_uuid') === '') {
            $data = $this->hydrateData($data);
        }

        $this->repository()->create(
            Arr::get($data, 'task_uuid'),
            Arr::get($data, 'action_uuid'),
            Arr::get($data, 'order'),
        );
        $entity = $this->repository()->get(Arr::get($data, 'task_uuid'), Arr::get($data, 'action_uuid'));
        $this->assertSameReturned($entity, $data);
        return $entity;
    }

    /**
     * Create new task entity
     * @return TaskInterface
     */
    private function createTaskEntity(): TaskInterface
    {
        $data = $this->taskData();
        $entity = $this->taskRepository()->create(
            Arr::get($data, 'name'),
            Arr::get($data, 'description'),
            Arr::get($data, 'type'),
        );
        $this->assertSameTaskReturned($entity, $data);
        return $entity;
    }

    /**
     * Create new action entity
     * @return ActionInterface
     */
    private function createActionEntity(): ActionInterface
    {
        $data = $this->actionData();
        $entity = $this->actionRepository()->create(
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
        $this->assertSameActionReturned($entity, $data);
        return $entity;
    }

    /**
     * Hydrate data array
     * @param array $data
     * @return array
     */
    private function hydrateData(array $data): array
    {
        Arr::set($data, 'task_uuid', $this->createTaskEntity()->getUuid());
        Arr::set($data, 'action_uuid', $this->createActionEntity()->getUuid());
        return $data;
    }

    /**
     * Entity data provider
     * @return \array[][]
     */
    public function entityDataProvider(): array
    {
        return [
            'example_task_action'   =>  [
                'data'              =>  [
                    'uuid'          =>  '0d6af9a3-b5ac-44f2-b3d9-3a92cc389de9',
                    'action_uuid'   =>  '',
                    'task_uuid'     =>  '',
                    'order'         =>  1,
                ],
            ],
        ];
    }

    /**
     * Task data provider
     * @return \array[][]
     */
    public function taskData(): array
    {
        return [
            'id'            =>  1,
            'uuid'          =>  '73f66d30-ad58-4641-8b25-05b245031b50',
            'name'          =>  'Example Task',
            'description'   =>  'Example Task Description',
            'type'          =>  1,
        ];
    }

    /**
     * Action data provider
     * @return array
     */
    public function actionData(): array
    {
        return [
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
        ];
    }
}
