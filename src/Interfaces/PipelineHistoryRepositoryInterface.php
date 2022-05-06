<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface PipelineHistoryRepositoryInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface PipelineHistoryRepositoryInterface
{
    /**
     * Get list of all entries from database
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return Collection
     */
    public function all(array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): Collection;

    /**
     * Find pipeline execution
     * @param int $id
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return PipelineExecutionInterface|null
     */
    public function find(int $id, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): PipelineExecutionInterface|null;

    /**
     * Find pipeline execution or fail and throw exception
     * @param int $id
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return PipelineExecutionInterface
     * @throws ModelNotFoundException
     */
    public function findOrFail(int $id, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): PipelineExecutionInterface;

    /**
     * Find pipeline execution by specified field
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return PipelineExecutionInterface|null
     */
    public function findBy(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): PipelineExecutionInterface|null;

    /**
     * Find pipeline execution by specified field or throw exception
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return PipelineExecutionInterface
     * @throws ModelNotFoundException
     */
    public function findByOrFail(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): PipelineExecutionInterface;

    /**
     * Find entity while using multiple fields to perform search
     * @param array $fields
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return PipelineExecutionInterface|null
     */
    public function findByMany(array $fields, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): PipelineExecutionInterface|null;

    /**
     * Find entity while using multiple fields to perform search or throw exception
     * @param array $fields
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return PipelineExecutionInterface
     * @throws ModelNotFoundException
     */
    public function findByManyOrFail(array $fields, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): PipelineExecutionInterface;

    /**
     * Create new pipeline execution
     * @param string $pipelineUuid
     * @param int $state
     * @return PipelineExecutionInterface
     */
    public function create(string $pipelineUuid, int $state): PipelineExecutionInterface;

    /**
     * Update existing pipeline execution
     * @param string|int $identifier
     * @param int $state
     * @return PipelineExecutionInterface
     */
    public function update(string|int $identifier, int $state): PipelineExecutionInterface;

    /**
     * Delete pipeline execution
     * @param string|int $identifier
     * @param bool $forceDelete
     * @return bool
     */
    public function delete(string|int $identifier, bool $forceDelete = false): bool;

    /**
     * Restore pipeline execution
     * @param string|int $identifier
     * @return bool
     */
    public function restore(string|int $identifier): bool;

    /**
     * Force pipeline execution deletion
     * @param string|int $identifier
     * @return bool
     */
    public function forceDelete(string|int $identifier): bool;
}
