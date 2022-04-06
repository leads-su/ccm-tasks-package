<?php

namespace ConsulConfigManager\Tasks\Repositories;

use Throwable;
use Illuminate\Support\Str;
use ConsulConfigManager\Tasks\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\AggregateRoots\TaskAggregateRoot;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;

/**
 * Class TaskRepository
 * @package ConsulConfigManager\Tasks\Repositories
 */
class TaskRepository extends AbstractRepository implements TaskRepositoryInterface
{
    /**
     * @inheritDoc
     */
    protected string $modelClass = Task::class;

    /**
     * @inheritDoc
     */
    public function all(array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): Collection
    {
        return $this->getModelQueryWithTrashed($withDeleted)->with($with)->get($columns)->each->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function find(int $id, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): TaskInterface|null
    {
        return $this->findBy(
            field: 'id',
            value: $id,
            columns: $columns,
            with: $with,
            append: $append,
            withDeleted: $withDeleted,
        );
    }

    /**
     * @inheritDoc
     */
    public function findOrFail(int $id, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): TaskInterface
    {
        return $this->findByOrFail(
            field: 'id',
            value: $id,
            columns: $columns,
            with: $with,
            append: $append,
            withDeleted: $withDeleted,
        );
    }

    /**
     * @inheritDoc
     */
    public function findBy(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): TaskInterface|null
    {
        return $this->getModelQueryWithTrashed($withDeleted)->with($with)->where($field, '=', $value)->first($columns)?->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findByOrFail(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): TaskInterface
    {
        return $this->getModelQueryWithTrashed($withDeleted)->with($with)->where($field, '=', $value)->firstOrFail($columns)->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findByMany(array $fields, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): TaskInterface|null
    {
        $query = $this->mapModelMultipleQuery(
            fields: $fields,
            value: $value,
            with: $with,
            withDeleted: $withDeleted,
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
    public function findByManyOrFail(array $fields, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): TaskInterface
    {
        $query = $this->mapModelMultipleQuery(
            fields: $fields,
            value: $value,
            with: $with,
            withDeleted: $withDeleted,
        );
        return $query->firstOrFail($columns)->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function create(string $name, string $description, bool $failOnError = false): TaskInterface
    {
        $uuid = Str::uuid()->toString();
        TaskAggregateRoot::retrieve($uuid)
            ->createEntity(
                $name,
                $description,
                $failOnError,
            )
            ->persist();
        return Task::uuid($uuid);
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, string $name, string $description, bool $failOnError = false): TaskInterface
    {
        $model = $this->findOrFail(
            id: $id,
            columns: ['uuid']
        );
        TaskAggregateRoot::retrieve($model->getUuid())
            ->updateEntity(
                $name,
                $description,
                $failOnError,
            )
            ->persist();
        return Task::uuid($model->getUuid());
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, bool $forceDelete = false): bool
    {
        try {
            $model = $this->findOrFail(
                id: $id,
                columns: ['uuid']
            );
            TaskAggregateRoot::retrieve($model->getUuid())
                ->deleteEntity()
                ->persist();
            return true;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function restore(int $id): bool
    {
        try {
            $model = $this->findOrFail(
                id: $id,
                columns: ['uuid'],
                withDeleted: true,
            );
            TaskAggregateRoot::retrieve($model->getUuid())
                ->restoreEntity()
                ->persist();
            return true;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function forceDelete(int $id): bool
    {
        return $this->delete($id, true);
    }
}
