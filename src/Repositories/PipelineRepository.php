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
class PipelineRepository implements PipelineRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function all(array $columns = ['*']): Collection
    {
        return Pipeline::all($columns);
    }

    /**
     * @inheritDoc
     */
    public function find(int $id, array $columns = ['*']): PipelineInterface|null
    {
        return $this->findBy('id', $id, $columns);
    }

    /**
     * @inheritDoc
     */
    public function findOrFail(int $id, array $columns = ['*']): PipelineInterface
    {
        return $this->findByOrFail('id', $id, $columns);
    }

    /**
     * @inheritDoc
     */
    public function findBy(string $field, string $value, array $columns = ['*']): PipelineInterface|null
    {
        return Pipeline::where($field, '=', $value)->first($columns);
    }

    /**
     * @inheritDoc
     */
    public function findByOrFail(string $field, string $value, array $columns = ['*']): PipelineInterface
    {
        return Pipeline::where($field, '=', $value)->firstOrFail($columns);
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
    public function forceDelete(int $id): bool
    {
        return $this->delete($id, true);
    }
}
