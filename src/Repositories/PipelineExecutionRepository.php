<?php

namespace ConsulConfigManager\Tasks\Repositories;

use Throwable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use ConsulConfigManager\Tasks\Models\PipelineExecution;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionInterface;
use ConsulConfigManager\Tasks\AggregateRoots\PipelineExecutionAggregateRoot;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionRepositoryInterface;

/**
 * Class PipelineExecutionRepository
 * @package ConsulConfigManager\Tasks\Repositories
 */
class PipelineExecutionRepository extends AbstractRepository implements PipelineExecutionRepositoryInterface
{
    /**
     * @inheritDoc
     */
    protected string $modelClass = PipelineExecution::class;

    /**
     * @inheritDoc
     */
    public function all(array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): Collection
    {
        return $this->getModelQueryWithTrashed($withDeleted)
            ->with($with)
            ->orderByDesc('created_at')
            ->get($columns)
            ->each
            ->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function find(int $id, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): PipelineExecutionInterface|null
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
    public function findOrFail(int $id, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): PipelineExecutionInterface
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
    public function findBy(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): PipelineExecutionInterface|null
    {
        return $this->getModelQueryWithTrashed($withDeleted)
            ->with($with)
            ->where($field, '=', $value)
            ->first($columns)
            ?->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findManyBy(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): Collection
    {
        return $this->getModelQuery()->with($with)->where($field, '=', $value)->get($columns)->each->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findByOrFail(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): PipelineExecutionInterface
    {
        return $this->getModelQueryWithTrashed($withDeleted)
            ->with($with)
            ->where($field, '=', $value)
            ->firstOrFail($columns)
            ->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findByMany(array $fields, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): PipelineExecutionInterface|null
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
    public function findByManyOrFail(array $fields, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): PipelineExecutionInterface
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
    public function findByManyFromMappings(array $mappings, array $columns = ['*'], array $with = [], array $append = []): PipelineExecutionInterface|null
    {
        $query = PipelineExecution::with($with);
        foreach ($mappings as $key => $value) {
            $query = $query->where($key, '=', $value);
        }
        return $query->first($columns)?->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findByManyFromMappingsOrFail(array $mappings, array $columns = ['*'], array $with = [], array $append = []): PipelineExecutionInterface
    {
        $query = PipelineExecution::with($with);
        foreach ($mappings as $key => $value) {
            $query = $query->where($key, '=', $value);
        }
        return $query->firstOrFail($columns)->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function create(string $pipelineUuid, int $state): PipelineExecutionInterface
    {
        $uuid = Str::uuid()->toString();
        PipelineExecutionAggregateRoot::retrieve($uuid)
            ->createEntity(
                $pipelineUuid,
                $state
            )
            ->persist();
        return PipelineExecution::uuid($uuid);
    }

    /**
     * @inheritDoc
     */
    public function update(string|int $identifier, int $state): PipelineExecutionInterface
    {
        $model = $this->findByManyOrFail(
            fields: ['id', 'uuid'],
            value: $identifier,
            columns: ['uuid', 'pipeline_uuid']
        );
        PipelineExecutionAggregateRoot::retrieve($model->getUuid())
            ->updateEntity(
                $model->getPipelineUuid(),
                $state
            )
            ->persist();
        return PipelineExecution::uuid($model->getUuid());
    }

    /**
     * @inheritDoc
     */
    public function delete(string|int $identifier, bool $forceDelete = false): bool
    {
        try {
            $model = $this->findByManyOrFail(
                fields: ['id', 'uuid'],
                value: $identifier,
                columns: ['uuid']
            );
            PipelineExecutionAggregateRoot::retrieve($model->getUuid())
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
    public function restore(string|int $identifier): bool
    {
        try {
            $model = $this->findByManyOrFail(
                fields: ['id', 'uuid'],
                value: $identifier,
                columns: ['uuid'],
                withDeleted: true,
            );
            PipelineExecutionAggregateRoot::retrieve($model->getUuid())
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
    public function forceDelete(string|int $identifier): bool
    {
        return $this->delete($identifier, true);
    }
}
