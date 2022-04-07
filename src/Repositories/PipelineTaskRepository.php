<?php

namespace ConsulConfigManager\Tasks\Repositories;

use Illuminate\Support\Str;
use ConsulConfigManager\Tasks\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use ConsulConfigManager\Tasks\Models\Pipeline;
use ConsulConfigManager\Tasks\Models\PipelineTask;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;
use ConsulConfigManager\Tasks\Exceptions\ModelAlreadyExistsException;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;
use ConsulConfigManager\Tasks\AggregateRoots\PipelineTaskAggregateRoot;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskRepositoryInterface;

/**
 * Class PipelineTaskRepository
 * @package ConsulConfigManager\Tasks\Repositories
 */
class PipelineTaskRepository implements PipelineTaskRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function list(int|string $pipelineIdentifier, array $with = [], array $append = [], bool $withDeleted = false): Collection
    {
        $with['task'] = function ($query): void {
            $query->select(['id', 'uuid', 'name', 'description']);
        };
        return PipelineTask::withTrashed($withDeleted)
            ->with($with)
            ->where('pipeline_uuid', '=', $this->resolvePipelineUUID($pipelineIdentifier, $withDeleted))
            ->get()->each->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function get(int|string $pipelineIdentifier, int|string $taskIdentifier, array $with = [], array $append = []): PipelineTaskInterface
    {
        return $this->resolvePipelineTask(
            $pipelineIdentifier,
            $taskIdentifier,
            with: $with,
            append: $append,
        );
    }

    /**
     * @inheritDoc
     */
    public function exists(string|int $pipelineIdentifier, string|int $taskIdentifier): bool
    {
        $pipelineIdentifier = $this->resolvePipelineUUID($pipelineIdentifier);
        $taskIdentifier = $this->resolveTaskUUID($taskIdentifier);

        return PipelineTask::withTrashed(true)
            ->where('pipeline_uuid', '=', $pipelineIdentifier)
            ->where('task_uuid', '=', $taskIdentifier)
            ->exists();
    }

    /**
     * @inheritDoc
     */
    public function getPipelineTasks(string|int $pipelineIdentifier): Collection
    {
        $pipelineIdentifier = $this->resolvePipelineUUID($pipelineIdentifier);
        return PipelineTask::withTrashed(true)->where('pipeline_uuid', '=', $pipelineIdentifier)->get();
    }

    /**
     * @inheritDoc
     */
    public function create(int|string $pipelineIdentifier, int|string $taskIdentifier, int $order): bool
    {
        $uuid = Str::uuid()->toString();
        $pipelineIdentifier = $this->resolvePipelineUUID($pipelineIdentifier);
        $taskIdentifier = $this->resolveTaskUUID($taskIdentifier);

        if ($this->exists($pipelineIdentifier, $taskIdentifier)) {
            throw (new ModelAlreadyExistsException())->setModel(PipelineTask::class);
        }

        PipelineTaskAggregateRoot::retrieve($uuid)
            ->createEntity(
                $pipelineIdentifier,
                $taskIdentifier,
                $order,
            )->persist();
        return true;
    }

    /**
     * @inheritDoc
     */
    public function update(int|string $pipelineIdentifier, int|string $taskIdentifier, int $order): bool
    {
        $this->resolvePipelineTaskAggregateRoot($pipelineIdentifier, $taskIdentifier)
            ->updateEntity($order)
            ->persist();
        return true;
    }

    /**
     * @inheritDoc
     */
    public function delete(int|string $pipelineIdentifier, int|string $taskIdentifier, bool $force = false): bool
    {
        $this->resolvePipelineTaskAggregateRoot($pipelineIdentifier, $taskIdentifier)
            ->deleteEntity($force)
            ->persist();
        return true;
    }

    /**
     * @inheritDoc
     */
    public function forceDelete(int|string $pipelineIdentifier, int|string $taskIdentifier): bool
    {
        return $this->delete(
            $pipelineIdentifier,
            $taskIdentifier,
            true,
        );
    }

    /**
     * @inheritDoc
     */
    public function restore(int|string $pipelineIdentifier, int|string $taskIdentifier): bool
    {
        $this->resolvePipelineTaskAggregateRoot($pipelineIdentifier, $taskIdentifier)
            ->restoreEntity()
            ->persist();
        return true;
    }

    /**
     * Resolve pipeline task by specified parameters
     * @param string|int $pipelineIdentifier
     * @param string|int $taskIdentifier
     * @param array $columns
     * @param array $with
     * @param array $append
     * @return PipelineTaskInterface
     * @throws BindingResolutionException
     */
    public function resolvePipelineTask(string|int $pipelineIdentifier, string|int $taskIdentifier, array $columns = ['*'], array $with = [], array $append = []): PipelineTaskInterface
    {
        $pipelineIdentifier = $this->resolvePipelineUUID($pipelineIdentifier);
        $taskIdentifier = $this->resolveTaskUUID($taskIdentifier);
        return $this->findPipelineTask(
            pipelineIdentifier: $pipelineIdentifier,
            taskIdentifier: $taskIdentifier,
            columns: $columns,
            with: $with,
            append: $append,
        );
    }

    /**
     * Resolve pipeline task aggregate root
     * @param string|int $pipelineIdentifier
     * @param string|int $taskIdentifier
     * @return PipelineTaskAggregateRoot
     * @throws BindingResolutionException
     */
    public function resolvePipelineTaskAggregateRoot(string|int $pipelineIdentifier, string|int $taskIdentifier): PipelineTaskAggregateRoot
    {
        $pipelineTaskInstance = $this->resolvePipelineTask($pipelineIdentifier, $taskIdentifier, ['uuid']);
        return PipelineTaskAggregateRoot::retrieve($pipelineTaskInstance->getUuid());
    }

    /**
     * @inheritDoc
     */
    public function findPipeline(int|string $id, array $columns = ['*'], bool $withDeleted = false): PipelineInterface
    {
        return $this->pipelineRepository()
            ->findByManyOrFail(
                fields: ['id', 'uuid'],
                value: $id,
                columns: $columns,
                withDeleted: $withDeleted
            );
    }

    /**
     * @inheritDoc
     */
    public function findTask(int|string $id, array $columns = ['*'], bool $withDeleted = false): TaskInterface
    {
        return $this->taskRepository()
            ->findByManyOrFail(
                fields: ['id', 'uuid'],
                value: $id,
                columns: $columns,
                withDeleted: $withDeleted
            );
    }

    /**
     * Find task action by specified parameters
     * @param string $pipelineIdentifier
     * @param string $taskIdentifier
     * @param array $columns
     * @param array $with
     * @param array $append
     * @return PipelineTaskInterface
     */
    public function findPipelineTask(string $pipelineIdentifier, string $taskIdentifier, array $columns = ['*'], array $with = [], array $append = []): PipelineTaskInterface
    {
        return PipelineTask::withTrashed(true)
            ->where('pipeline_uuid', '=', $pipelineIdentifier)
            ->where('task_uuid', '=', $taskIdentifier)
            ->with($with)
            ->firstOrFail($columns)->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function resolvePipelineUUID(int|string $id, bool $withDeleted = false): string
    {
        if (is_string($id) && is_numeric($id) || is_integer($id)) {
            $id = $this->findPipeline(
                id: $id,
                columns: ['uuid'],
                withDeleted: $withDeleted
            )->getUuid();
        }
        if (!$this->pipelineExists($id)) {
            throw (new ModelNotFoundException())->setModel(Pipeline::class);
        }
        return $id;
    }

    /**
     * @inheritDoc
     */
    public function resolveTaskUUID(int|string $id, bool $withDeleted = false): string
    {
        if (is_numeric($id) || is_integer($id)) {
            $id = $this->findTask(
                id: $id,
                columns: ['uuid'],
                withDeleted: $withDeleted
            )->getUuid();
        }
        if (!$this->taskExists($id)) {
            throw (new ModelNotFoundException())->setModel(Task::class);
        }
        return $id;
    }

    /**
     * @inheritDoc
     */
    public function isBound(int|string $pipelineIdentifier, int|string $taskIdentifier, bool $withDeleted = false, bool $return = false): bool|PipelineTaskInterface
    {
        $query = PipelineTask::withTrashed($withDeleted)
            ->where('pipeline_uuid', '=', $this->resolvePipelineUUID($pipelineIdentifier))
            ->where('task_uuid', '=', $this->resolveTaskUUID($taskIdentifier));

        if ($return) {
            return $query->firstOrFail();
        }
        return $query->exists();
    }

    /**
     * @inheritDoc
     */
    public function pipelineTaskUUID(int|string $pipelineIdentifier, int|string $taskIdentifier, bool $withDeleted = false): string
    {
        try {
            $model = $this->isBound(
                pipelineIdentifier: $pipelineIdentifier,
                taskIdentifier: $taskIdentifier,
                withDeleted: $withDeleted,
                return: true
            );
            return $model->getUuid();
        } catch (ModelNotFoundException) {
            return '';
        }
    }

    /**
     * @inheritDoc
     */
    public function pipelineExists(string $pipelineIdentifier): bool
    {
        return $this->pipelineRepository()->findBy(
            field: 'uuid',
            value: $pipelineIdentifier
        ) !== null;
    }

    /**
     * @inheritDoc
     */
    public function taskExists(string $taskIdentifier): bool
    {
        return $this->taskRepository()->findBy(
            field: 'uuid',
            value: $taskIdentifier
        ) !== null;
    }

    /**
     * Get instance of pipeline repository
     * @return PipelineRepositoryInterface
     * @throws BindingResolutionException
     */
    private function pipelineRepository(): PipelineRepositoryInterface
    {
        return app()->make(PipelineRepositoryInterface::class);
    }

    /**
     * Get instance of task repository
     * @return TaskRepositoryInterface
     * @throws BindingResolutionException
     */
    private function taskRepository(): TaskRepositoryInterface
    {
        return app()->make(TaskRepositoryInterface::class);
    }
}
