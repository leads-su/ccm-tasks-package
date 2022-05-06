<?php

namespace ConsulConfigManager\Tasks\Repositories;

use Throwable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use ConsulConfigManager\Tasks\Models\Pipeline;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Tasks\AggregateRoots\PipelineAggregateRoot;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;

/**
 * Class PipelineRepository
 * @package ConsulConfigManager\Tasks\Repositories
 */
class PipelineRepository extends AbstractRepository implements PipelineRepositoryInterface
{
    /**
     * @inheritDoc
     */
    protected string $modelClass = Pipeline::class;

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
    public function find(int $id, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): PipelineInterface|null
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
    public function findOrFail(int $id, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): PipelineInterface
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
    public function findBy(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): PipelineInterface|null
    {
        return $this->getModelQueryWithTrashed($withDeleted)->with($with)->where($field, '=', $value)->first($columns)?->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findByOrFail(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): PipelineInterface
    {
        return $this->getModelQueryWithTrashed($withDeleted)->with($with)->where($field, '=', $value)->firstOrFail($columns)->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findByMany(array $fields, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): PipelineInterface|null
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
    public function findByManyOrFail(array $fields, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): PipelineInterface
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
    public function create(string $name, string $description): PipelineInterface
    {
        $uuid = Str::uuid()->toString();
        PipelineAggregateRoot::retrieve($uuid)
            ->createEntity(
                $name,
                $description
            )
            ->persist();
        return Pipeline::uuid($uuid);
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, string $name, string $description): PipelineInterface
    {
        $model = $this->findOrFail($id, ['uuid']);
        PipelineAggregateRoot::retrieve($model->getUuid())
            ->updateEntity(
                $name,
                $description
            )
            ->persist();
        return Pipeline::uuid($model->getUuid());
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, bool $forceDelete = false): bool
    {
        try {
            $model = $this->findOrFail($id, ['uuid']);
            PipelineAggregateRoot::retrieve($model->getUuid())
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
                withDeleted: true
            );
            PipelineAggregateRoot::retrieve($model->getUuid())
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
