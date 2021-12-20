<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface ActionRepositoryInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface ActionRepositoryInterface
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
     * Find action
     * @param int $id
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return ActionInterface|null
     */
    public function find(int $id, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): ActionInterface|null;

    /**
     * Find action or fail and throw exception
     * @param int $id
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return ActionInterface
     * @throws ModelNotFoundException
     */
    public function findOrFail(int $id, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): ActionInterface;

    /**
     * Find action by specified field
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return ActionInterface|null
     */
    public function findBy(string $field, string $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): ActionInterface|null;

    /**
     * Find action by specified field or throw exception
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return ActionInterface
     * @throws ModelNotFoundException
     */
    public function findByOrFail(string $field, string $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): ActionInterface;

    /**
     * Find entity while using multiple fields to perform search
     * @param array $fields
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return ActionInterface|null
     */
    public function findByMany(array $fields, string $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): ActionInterface|null;

    /**
     * Find entity while using multiple fields to perform search or throw exception
     * @param array $fields
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return ActionInterface
     * @throws ModelNotFoundException
     */
    public function findByManyOrFail(array $fields, string $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): ActionInterface;

    /**
     * Create new action
     * @param string $name
     * @param string $description
     * @param int $type
     * @param string $command
     * @param array $arguments
     * @param string|null $workingDirectory
     * @param string|null $runAs
     * @param bool $useSudo
     * @param bool $failOnError
     * @return ActionInterface
     */
    public function create(
        string $name,
        string $description,
        int $type,
        string $command,
        array $arguments,
        ?string $workingDirectory = null,
        ?string $runAs = null,
        bool $useSudo = false,
        bool $failOnError = true,
    ): ActionInterface;

    /**
     * Update existing action
     * @param int $id
     * @param string $name
     * @param string $description
     * @param int $type
     * @param string $command
     * @param array $arguments
     * @param string|null $workingDirectory
     * @param string|null $runAs
     * @param bool $useSudo
     * @param bool $failOnError
     * @return ActionInterface
     */
    public function update(
        int $id,
        string $name,
        string $description,
        int $type,
        string $command,
        array $arguments,
        ?string $workingDirectory = null,
        ?string $runAs = null,
        bool $useSudo = false,
        bool $failOnError = true,
    ): ActionInterface;


    /**
     * Delete action
     * @param int $id
     * @param bool $forceDelete
     * @return bool
     */
    public function delete(int $id, bool $forceDelete = false): bool;

    /**
     * Restore action
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool;

    /**
     * Force action deletion
     * @param int $id
     * @return bool
     */
    public function forceDelete(int $id): bool;
}
