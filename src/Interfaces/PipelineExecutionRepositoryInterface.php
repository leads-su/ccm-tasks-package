<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface PipelineExecutionRepositoryInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface PipelineExecutionRepositoryInterface
{
    /**
     * Get list of all entries from database
     * @param array|string[] $columns
     * @param bool $withDeleted
     * @return Collection
     */
    public function all(array $columns = ['*'], bool $withDeleted = false): Collection;

    /**
     * Find pipeline execution
     * @param int $id
     * @param array|string[] $columns
     * @param bool $withDeleted
     * @return PipelineExecutionInterface|null
     */
    public function find(int $id, array $columns = ['*'], bool $withDeleted = false): PipelineExecutionInterface|null;

    /**
     * Find pipeline execution or fail and throw exception
     * @param int $id
     * @param array|string[] $columns
     * @param bool $withDeleted
     * @return PipelineExecutionInterface
     * @throws ModelNotFoundException
     */
    public function findOrFail(int $id, array $columns = ['*'], bool $withDeleted = false): PipelineExecutionInterface;

    /**
     * Find pipeline execution by specified field
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @param bool $withDeleted
     * @return PipelineExecutionInterface|null
     */
    public function findBy(string $field, string $value, array $columns = ['*'], bool $withDeleted = false): PipelineExecutionInterface|null;

    /**
     * Find pipeline execution by specified field or throw exception
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @param bool $withDeleted
     * @return PipelineExecutionInterface
     * @throws ModelNotFoundException
     */
    public function findByOrFail(string $field, string $value, array $columns = ['*'], bool $withDeleted = false): PipelineExecutionInterface;

    /**
     * Create new pipeline execution
     * @param string $pipelineUuid
     * @param int $state
     * @return PipelineExecutionInterface
     */
    public function create(string $pipelineUuid, int $state): PipelineExecutionInterface;

    /**
     * Update existing pipeline execution
     * @param int $id
     * @param string $pipelineUuid
     * @param int $state
     * @return PipelineExecutionInterface
     */
    public function update(int $id, string $pipelineUuid, int $state): PipelineExecutionInterface;

    /**
     * Delete pipeline execution
     * @param int $id
     * @param bool $forceDelete
     * @return bool
     */
    public function delete(int $id, bool $forceDelete = false): bool;

    /**
     * Restore pipeline execution
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool;

    /**
     * Force pipeline execution deletion
     * @param int $id
     * @return bool
     */
    public function forceDelete(int $id): bool;
}
