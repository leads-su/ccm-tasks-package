<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface TaskExecutionRepositoryInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface TaskExecutionRepositoryInterface
{
    /**
     * Get list of all entries from database
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @return Collection
     */
    public function all(array $columns = ['*'], array $with = [], array $append = []): Collection;

    /**
     * Find entity by primary key
     * @param int $id
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @return TaskExecutionInterface|null
     */
    public function find(int $id, array $columns = ['*'], array $with = [], array $append = []): TaskExecutionInterface|null;

    /**
     * Find entity by primary key or fail and throw exception
     * @param int $id
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @return TaskExecutionInterface
     * @throws ModelNotFoundException
     */
    public function findOrFail(int $id, array $columns = ['*'], array $with = [], array $append = []): TaskExecutionInterface;

    /**
     * Find entity by specified field
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @return TaskExecutionInterface|null
     */
    public function findBy(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = []): TaskExecutionInterface|null;

    /**
     * Find entity by specified field or throw exception
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @return TaskExecutionInterface
     * @throws ModelNotFoundException
     */
    public function findByOrFail(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = []): TaskExecutionInterface;

    /**
     * Find entity while using multiple fields to perform search
     * @param array $fields
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @return TaskExecutionInterface|null
     */
    public function findByMany(array $fields, mixed $value, array $columns = ['*'], array $with = [], array $append = []): TaskExecutionInterface|null;

    /**
     * Find entity while using multiple fields to perform search or throw exception
     * @param array $fields
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @return TaskExecutionInterface
     * @throws ModelNotFoundException
     */
    public function findByManyOrFail(array $fields, mixed $value, array $columns = ['*'], array $with = [], array $append = []): TaskExecutionInterface;

    /**
     * Create new entity
     * @param string $taskIdentifier
     * @param string $pipelineIdentifier
     * @param string $executionIdentifier
     * @param int $state
     * @return TaskExecutionInterface
     */
    public function create(string $taskIdentifier, string $pipelineIdentifier, string $executionIdentifier, int $state = ExecutionState::CREATED): TaskExecutionInterface;

    /**
     * Update existing entity
     * @param string $taskIdentifier
     * @param string $pipelineIdentifier
     * @param string $executionIdentifier
     * @param int $state
     * @return TaskExecutionInterface
     */
    public function update(string $taskIdentifier, string $pipelineIdentifier, string $executionIdentifier, int $state = ExecutionState::WAITING): TaskExecutionInterface;

    /**
     * Update existing entity by id
     * @param int $id
     * @param int $state
     * @return TaskExecutionInterface
     */
    public function updateById(int $id, int $state): TaskExecutionInterface;

    /**
     * Delete entity
     * @param string|int $id
     * @param bool $forceDelete
     * @return bool
     */
    public function delete(string|int $id, bool $forceDelete = false): bool;

    /**
     * Force entity deletion
     * @param string|int $id
     * @return bool
     */
    public function forceDelete(string|int $id): bool;
}
