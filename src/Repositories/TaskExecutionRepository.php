<?php

namespace ConsulConfigManager\Tasks\Repositories;

use Throwable;
use Illuminate\Database\Eloquent\Collection;
use ConsulConfigManager\Tasks\Enums\ExecutionState;
use ConsulConfigManager\Tasks\Models\TaskExecution;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionRepositoryInterface;

/**
 * Class TaskExecutionRepository
 * @package ConsulConfigManager\Tasks\Repositories
 */
class TaskExecutionRepository implements TaskExecutionRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function all(array $columns = ['*'], array $with = [], array $append = []): Collection
    {
        return TaskExecution::with($with)->get($columns)->each->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function find(int $id, array $columns = ['*'], array $with = [], array $append = []): TaskExecutionInterface|null
    {
        return $this->findBy(
            field: 'id',
            value: $id,
            columns: $columns,
            with: $with,
            append: $append,
        );
    }

    /**
     * @inheritDoc
     */
    public function findOrFail(int $id, array $columns = ['*'], array $with = [], array $append = []): TaskExecutionInterface
    {
        return $this->findByOrFail(
            field: 'id',
            value: $id,
            columns: $columns,
            with: $with,
            append: $append,
        );
    }

    /**
     * @inheritDoc
     */
    public function findBy(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = []): TaskExecutionInterface|null
    {
        return TaskExecution::with($with)->where($field, '=', $value)->first()?->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findByOrFail(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = []): TaskExecutionInterface
    {
        return TaskExecution::with($with)->where($field, '=', $value)->firstOrFail()->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findByMany(array $fields, mixed $value, array $columns = ['*'], array $with = [], array $append = []): TaskExecutionInterface|null
    {
        $query = TaskExecution::with($with);
        foreach ($fields as $index => $field) {
            if ($index === 0) {
                $query = $query->where($field, '=', $value);
            } else {
                $query = $query->orWhere($field, '=', $value);
            }
        }
        $result = $query->first($columns);
        if (!$result) {
            return $result;
        }
        return $result->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findByManyOrFail(array $fields, mixed $value, array $columns = ['*'], array $with = [], array $append = []): TaskExecutionInterface
    {
        $query = TaskExecution::with($with);
        foreach ($fields as $index => $field) {
            if ($index === 0) {
                $query = $query->where($field, '=', $value);
            } else {
                $query = $query->orWhere($field, '=', $value);
            }
        }
        return $query->firstOrFail($columns)->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function create(string $taskIdentifier, string $pipelineIdentifier, string $executionIdentifier, int $state = ExecutionState::CREATED): TaskExecutionInterface
    {
        $model = new TaskExecution();
        $model->setTaskUuid($taskIdentifier);
        $model->setPipelineUuid($pipelineIdentifier);
        $model->setPipelineExecutionUuid($executionIdentifier);
        $model->setState($state);
        $model->save();
        return $model;
    }

    /**
     * @inheritDoc
     */
    public function update(string $taskIdentifier, string $pipelineIdentifier, string $executionIdentifier, int $state = ExecutionState::WAITING): TaskExecutionInterface
    {
        $model = (new TaskExecution())
            ->where('task_uuid', '=', $taskIdentifier)
            ->where('pipeline_uuid', '=', $pipelineIdentifier)
            ->where('pipeline_execution_uuid', '=', $executionIdentifier)
            ->firstOrFail();
        $model->setState($state);
        $model->save();
        return $model;
    }

    /**
     * @inheritDoc
     */
    public function updateById(int $id, int $state): TaskExecutionInterface
    {
        $model = TaskExecution::findOrFail($id);
        $model->setState($state);
        $model->save();
        return $model;
    }

    /**
     * @inheritDoc
     */
    public function delete(int|string $id, bool $forceDelete = false): bool
    {
        try {
            $model = TaskExecution::findOrFail($id);
            return $model->delete();
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function forceDelete(int|string $id): bool
    {
        return $this->delete($id, true);
    }
}
