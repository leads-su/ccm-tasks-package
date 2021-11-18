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
     *
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Find action
     * @param int $id
     * @param array|string[] $columns
     * @return ActionInterface|null
     */
    public function find(int $id, array $columns = ['*']): ActionInterface|null;

    /**
     * Find action or fail and throw exception
     * @param int $id
     * @param array|string[] $columns
     * @return ActionInterface
     * @throws ModelNotFoundException
     */
    public function findOrFail(int $id, array $columns = ['*']): ActionInterface;

    /**
     * Find action by specified field
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @return ActionInterface|null
     */
    public function findBy(string $field, string $value, array $columns = ['*']): ActionInterface|null;

    /**
     * Find action by specified field or throw exception
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @throws ModelNotFoundException
     * @return ActionInterface
     */
    public function findByOrFail(string $field, string $value, array $columns = ['*']): ActionInterface;

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
     * Force action deletion
     * @param int $id
     * @return bool
     */
    public function forceDelete(int $id): bool;
}
