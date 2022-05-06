<?php

namespace ConsulConfigManager\Tasks\Repositories;

use Throwable;
use Illuminate\Database\Eloquent\Collection;
use ConsulConfigManager\Tasks\Enums\ExecutionState;
use ConsulConfigManager\Tasks\Models\ActionExecution;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionRepositoryInterface;

/**
 * Class ActionExecutionRepository
 * @package ConsulConfigManager\Tasks\Repositories
 */
class ActionExecutionRepository extends AbstractRepository implements ActionExecutionRepositoryInterface
{
    /**
     * @inheritDoc
     */
    protected string $modelClass = ActionExecution::class;

    /**
     * @inheritDoc
     */
    public function all(array $columns = ['*'], array $with = [], array $append = []): Collection
    {
        return $this->getModelQuery()->with($with)->get($columns)->each->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function find(int $id, array $columns = ['*'], array $with = [], array $append = []): ActionExecutionInterface|null
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
    public function findOrFail(int $id, array $columns = ['*'], array $with = [], array $append = []): ActionExecutionInterface
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
    public function findBy(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = []): ActionExecutionInterface|null
    {
        return $this->getModelQuery()->with($with)->where($field, '=', $value)->first($columns)?->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findManyBy(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = []): Collection
    {
        return $this->getModelQuery()->with($with)->where($field, '=', $value)->get($columns)->each->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findByOrFail(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = []): ActionExecutionInterface
    {
        return $this->getModelQuery()->with($with)->where($field, '=', $value)->firstOrFail($columns)->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findByMany(array $fields, mixed $value, array $columns = ['*'], array $with = [], array $append = []): ActionExecutionInterface|null
    {
        $query = $this->mapModelMultipleQuery(
            fields: $fields,
            value: $value,
            with: $with,
        );
        $result = $query->first($columns);
        if (!$result) {
            return $result;
        }
        return $result->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findByManyFromMappings(array $mappings, array $columns = ['*'], array $with = [], array $append = []): ActionExecutionInterface|null
    {
        $query = ActionExecution::with($with);
        foreach ($mappings as $key => $value) {
            $query = $query->where($key, '=', $value);
        }
        return $query->first($columns)?->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findByManyFromMappingsOrFail(array $mappings, array $columns = ['*'], array $with = [], array $append = []): ActionExecutionInterface
    {
        $query = ActionExecution::with($with);
        foreach ($mappings as $key => $value) {
            $query = $query->where($key, '=', $value);
        }
        return $query->firstOrFail($columns)->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findManyByMany(array $mappings, array $columns = ['*'], array $with = [], array $append = []): Collection
    {
        $query = ActionExecution::with($with);
        foreach ($mappings as $key => $value) {
            $query = $query->where($key, '=', $value);
        }

        return $query->get($columns)->each->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findByManyOrFail(array $fields, mixed $value, array $columns = ['*'], array $with = [], array $append = []): ActionExecutionInterface
    {
        $query = $this->mapModelMultipleQuery(
            fields: $fields,
            value: $value,
            with: $with,
        );
        return $query->firstOrFail($columns)->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function create(string $serverIdentifier, string $actionIdentifier, string $taskIdentifier, string $pipelineIdentifier, string $executionIdentifier, int $state = ExecutionState::CREATED): ActionExecutionInterface
    {
        $model = new ActionExecution();
        $model->setServerUuid($serverIdentifier);
        $model->setActionUuid($actionIdentifier);
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
    public function update(string $serverIdentifier, string $actionIdentifier, string $taskIdentifier, string $pipelineIdentifier, string $executionIdentifier, int $state = ExecutionState::WAITING): ActionExecutionInterface
    {
        $model = (new ActionExecution())
            ->where('server_uuid', '=', $serverIdentifier)
            ->where('action_uuid', '=', $actionIdentifier)
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
    public function updateById(int $id, int $state): ActionExecutionInterface
    {
        $model = ActionExecution::findOrFail($id);
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
            $model = ActionExecution::findOrFail($id);
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
