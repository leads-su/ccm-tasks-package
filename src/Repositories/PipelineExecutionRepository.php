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
class PipelineExecutionRepository implements PipelineExecutionRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function all(array $columns = ['*']): Collection
    {
        return PipelineExecution::all($columns);
    }

    /**
     * @inheritDoc
     */
    public function find(int $id, array $columns = ['*']): PipelineExecutionInterface|null
    {
        return $this->findBy('id', $id, $columns);
    }

    /**
     * @inheritDoc
     */
    public function findOrFail(int $id, array $columns = ['*']): PipelineExecutionInterface
    {
        return $this->findByOrFail('id', $id, $columns);
    }

    /**
     * @inheritDoc
     */
    public function findBy(string $field, string $value, array $columns = ['*']): PipelineExecutionInterface|null
    {
        return PipelineExecution::where($field, '=', $value)->first($columns);
    }

    /**
     * @inheritDoc
     */
    public function findByOrFail(string $field, string $value, array $columns = ['*']): PipelineExecutionInterface
    {
        return PipelineExecution::where($field, '=', $value)->firstOrFail($columns);
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
    public function update(int $id, string $pipelineUuid, int $state): PipelineExecutionInterface
    {
        $model = $this->findOrFail($id, ['uuid']);
        PipelineExecutionAggregateRoot::retrieve($model->getUuid())
            ->updateEntity(
                $pipelineUuid,
                $state
            )
            ->persist();
        return PipelineExecution::uuid($model->getUuid());
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, bool $forceDelete = false): bool
    {
        try {
            $model = $this->findOrFail($id, ['uuid']);
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
    public function forceDelete(int $id): bool
    {
        return $this->delete($id, true);
    }
}
