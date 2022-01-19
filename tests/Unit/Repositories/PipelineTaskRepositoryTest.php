<?php

namespace ConsulConfigManager\Tasks\Test\backup\Repositories;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Models\Task;
use ConsulConfigManager\Tasks\Models\Pipeline;
use ConsulConfigManager\Tasks\Models\PipelineTask;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskRepositoryInterface;

/**
 * Class PipelineTaskRepositoryTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Repositories
 */
class PipelineTaskRepositoryTest extends AbstractRepositoryTest
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
        $this->repository()->update(Arr::get($data, 'pipeline_uuid'), Arr::get($data, 'task_uuid'), 2);
        $entity = $this->repository()->get(Arr::get($data, 'pipeline_uuid'), Arr::get($data, 'task_uuid'));
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
        $result = $this->repository()->delete(Arr::get($data, 'pipeline_uuid'), Arr::get($data, 'task_uuid'));
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
        $result = $this->repository()->forceDelete(Arr::get($data, 'pipeline_uuid'), Arr::get($data, 'task_uuid'));
        $this->assertTrue($result);
    }

    /**
     * @param array $data
     * @return void
     * @throws BindingResolutionException
     * @dataProvider entityDataProvider
     */
    public function testShouldPassIfPipelineAndTaskAreBoundAndPipelineTaskUuidReturned(array $data): void
    {
        $data = $this->hydrateData($data);
        $this->createEntity($data);
        $result = $this->repository()->pipelineTaskUUID(Arr::get($data, 'pipeline_uuid'), Arr::get($data, 'task_uuid'));
        $this->assertNotEquals(0, strlen($result));
    }

    /**
     * @param array $data
     * @return void
     * @throws BindingResolutionException
     * @dataProvider entityDataProvider
     */
    public function testShouldPassIfPipelineAndTaskAreNotBoundAndEmptyStringReturned(array $data): void
    {
        $data = $this->hydrateData($data);
        $this->createEntity($data);
        $result = $this->repository()->pipelineTaskUUID(Arr::get($data, 'pipeline_uuid'), $this->createTaskEntity()->getUuid());
        $this->assertEquals(0, strlen($result));
    }

    /**
     * @param array $data
     * @return void
     * @throws BindingResolutionException
     * @dataProvider entityDataProvider
     */
    public function testShouldPassIfPipelineAndTaskAreBoundAndTrueReturned(array $data): void
    {
        $data = $this->hydrateData($data);
        $this->createEntity($data);
        $result = $this->repository()->isBound(Arr::get($data, 'pipeline_uuid'), Arr::get($data, 'task_uuid'));
        $this->assertTrue($result);
    }

    /**
     * @param array $data
     * @return void
     * @throws BindingResolutionException
     * @dataProvider entityDataProvider
     */
    public function testShouldPassIfPipelineAndTaskAreNotBoundAndFalseReturned(array $data): void
    {
        $data = $this->hydrateData($data);
        $this->createEntity($data);
        $result = $this->repository()->isBound(Arr::get($data, 'pipeline_uuid'), $this->createTaskEntity()->getUuid());
        $this->assertFalse($result);
    }

    /**
     * Create new repository instance
     * @return PipelineTaskRepositoryInterface
     */
    private function repository(): PipelineTaskRepositoryInterface
    {
        return $this->app->make(PipelineTaskRepositoryInterface::class);
    }

    /**
     * Create new pipeline repository instance
     * @return PipelineRepositoryInterface
     */
    private function pipelineRepository(): PipelineRepositoryInterface
    {
        return $this->app->make(PipelineRepositoryInterface::class);
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
     * Assert that data returned is the same as in array
     * @param PipelineTaskInterface $entity
     * @param array $data
     * @return void
     */
    private function assertSameReturned(PipelineTaskInterface $entity, array $data)
    {
        $this->assertInstanceOf(PipelineTask::class, $entity);
        $this->assertArrayHasKey('uuid', $entity);
        $this->assertSame(Arr::get($data, 'pipeline_uuid'), $entity->getPipelineUuid());
        $this->assertSame(Arr::get($data, 'task_uuid'), $entity->getTaskUuid());
        $this->assertSame(Arr::get($data, 'order'), $entity->getOrder());
    }

    /**
     * Assert that data returned is the same as in array
     * @param PipelineInterface $entity
     * @param array $data
     * @return void
     */
    private function assertSamePipelineReturned(PipelineInterface $entity, array $data)
    {
        $this->assertInstanceOf(Pipeline::class, $entity);
        $this->assertArrayHasKey('id', $entity);
        $this->assertArrayHasKey('uuid', $entity);
        $this->assertSame(Arr::get($data, 'name'), $entity->getName());
        $this->assertSame(Arr::get($data, 'description'), $entity->getDescription());
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
        $this->assertSame(Arr::get($data, 'type'), $entity->getType());
    }

    /**
     * Create new entity
     * @param array $data
     * @return PipelineTaskInterface
     * @throws BindingResolutionException
     */
    private function createEntity(array $data): PipelineTaskInterface
    {
        if (Arr::get($data, 'pipeline_uuid') === '' || Arr::get($data, 'task_uuid') === '') {
            $data = $this->hydrateData($data);
        }

        $this->repository()->create(
            Arr::get($data, 'pipeline_uuid'),
            Arr::get($data, 'task_uuid'),
            Arr::get($data, 'order'),
        );
        $entity = $this->repository()->get(Arr::get($data, 'pipeline_uuid'), Arr::get($data, 'task_uuid'));
        $this->assertSameReturned($entity, $data);
        return $entity;
    }

    /**
     * Create new pipeline entity
     * @return PipelineInterface
     */
    private function createPipelineEntity(): PipelineInterface
    {
        $data = $this->pipelineData();
        $entity = $this->pipelineRepository()->create(
            Arr::get($data, 'name'),
            Arr::get($data, 'description'),
        );
        $this->assertSamePipelineReturned($entity, $data);
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
     * Hydrate data array
     * @param array $data
     * @return array
     */
    private function hydrateData(array $data): array
    {
        Arr::set($data, 'pipeline_uuid', $this->createPipelineEntity()->getUuid());
        Arr::set($data, 'task_uuid', $this->createTaskEntity()->getUuid());
        return $data;
    }

    /**
     * Entity data provider
     * @return \array[][]
     */
    public function entityDataProvider(): array
    {
        return [
            'example_pipeline_task'     =>  [
                'data'                  =>  [
                    'uuid'              =>  'a78e4748-e9bf-4df1-ace5-981ff235f46c',
                    'pipeline_uuid'     =>  '',
                    'task_uuid'         =>  '',
                    'order'             =>  1,
                ],
            ],
        ];
    }

    /**
     * Pipeline data provider
     * @return array
     */
    public function pipelineData(): array
    {
        return [
            'id'            =>  1,
            'uuid'          =>  '73f66d30-ad58-4641-8b25-05b245031b50',
            'name'          =>  'Example Pipeline',
            'description'   =>  'Example Pipeline Description',
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
}
