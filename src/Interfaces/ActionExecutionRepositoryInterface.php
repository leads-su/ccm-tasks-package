<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use ConsulConfigManager\Tasks\Enums\ExecutionState;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface ActionExecutionRepositoryInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface ActionExecutionRepositoryInterface
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
     * @return ActionExecutionInterface|null
     */
    public function find(int $id, array $columns = ['*'], array $with = [], array $append = []): ActionExecutionInterface|null;

    /**
     * Find entity by primary key or fail and throw exception
     * @param int $id
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @return ActionExecutionInterface
     * @throws ModelNotFoundException
     */
    public function findOrFail(int $id, array $columns = ['*'], array $with = [], array $append = []): ActionExecutionInterface;

    /**
     * Find entity by specified field
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @return ActionExecutionInterface|null
     */
    public function findBy(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = []): ActionExecutionInterface|null;

    /**
     * Find many entities by specified field
     * @param string $field
     * @param mixed $value
     * @param array $columns
     * @param array $with
     * @param array $append
     * @return Collection
     */
    public function findManyBy(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = []): Collection;

    /**
     * Find entity by specified field or throw exception
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @return ActionExecutionInterface
     * @throws ModelNotFoundException
     */
    public function findByOrFail(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = []): ActionExecutionInterface;

    /**
     * Find entity while using multiple fields to perform search
     * @param array $fields
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @return ActionExecutionInterface|null
     */
    public function findByMany(array $fields, mixed $value, array $columns = ['*'], array $with = [], array $append = []): ActionExecutionInterface|null;

    /**
     * Find entity while using mappings to perform search
     * @param array $mappings
     * @param array $columns
     * @param array $with
     * @param array $append
     * @return ActionExecutionInterface|null
     */
    public function findByManyFromMappings(array $mappings, array $columns = ['*'], array $with = [], array $append = []): ActionExecutionInterface|null;

    /**
     * Find entity while using mappings to perform search and fail if none found
     * @param array $mappings
     * @param array $columns
     * @param array $with
     * @param array $append
     * @throws ModelNotFoundException
     * @return ActionExecutionInterface
     */
    public function findByManyFromMappingsOrFail(array $mappings, array $columns = ['*'], array $with = [], array $append = []): ActionExecutionInterface;

    /**
     * Find many entities by many parameters
     * @param array $mappings
     * @param array $columns
     * @param array $with
     * @param array $append
     * @return Collection
     */
    public function findManyByMany(array $mappings, array $columns = ['*'], array $with = [], array $append = []): Collection;

    /**
     * Find entity while using multiple fields to perform search or throw exception
     * @param array $fields
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @return ActionExecutionInterface
     * @throws ModelNotFoundException
     */
    public function findByManyOrFail(array $fields, mixed $value, array $columns = ['*'], array $with = [], array $append = []): ActionExecutionInterface;

    /**
     * Create new entity
     * @param string $serverIdentifier
     * @param string $actionIdentifier
     * @param string $taskIdentifier
     * @param string $pipelineIdentifier
     * @param string $executionIdentifier
     * @param int $state
     * @return ActionExecutionInterface
     */
    public function create(string $serverIdentifier, string $actionIdentifier, string $taskIdentifier, string $pipelineIdentifier, string $executionIdentifier, int $state = ExecutionState::CREATED): ActionExecutionInterface;

    /**
     * Update existing entity
     * @param string $serverIdentifier
     * @param string $actionIdentifier
     * @param string $taskIdentifier
     * @param string $pipelineIdentifier
     * @param string $executionIdentifier
     * @param int $state
     * @return ActionExecutionInterface
     */
    public function update(string $serverIdentifier, string $actionIdentifier, string $taskIdentifier, string $pipelineIdentifier, string $executionIdentifier, int $state = ExecutionState::WAITING): ActionExecutionInterface;

    /**
     * Update existing entity by id
     * @param int $id
     * @param int $state
     * @return ActionExecutionInterface
     */
    public function updateById(int $id, int $state): ActionExecutionInterface;

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
