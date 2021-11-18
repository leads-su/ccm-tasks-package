<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface TaskRepositoryInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface TaskRepositoryInterface
{
    /**
     * Get list of all entries from database
     * @param array|string[] $columns
     *
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Find task
     * @param int $id
     * @param array|string[] $columns
     * @return TaskInterface|null
     */
    public function find(int $id, array $columns = ['*']): TaskInterface|null;

    /**
     * Find task or fail and throw exception
     * @param int $id
     * @param array|string[] $columns
     * @return TaskInterface
     * @throws ModelNotFoundException
     */
    public function findOrFail(int $id, array $columns = ['*']): TaskInterface;

    /**
     * Find task by specified field
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @return TaskInterface|null
     */
    public function findBy(string $field, string $value, array $columns = ['*']): TaskInterface|null;

    /**
     * Find task by specified field or throw exception
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @throws ModelNotFoundException
     * @return TaskInterface
     */
    public function findByOrFail(string $field, string $value, array $columns = ['*']): TaskInterface;

    /**
     * Create new task
     * @param string $name
     * @param string $description
     * @param int $type
     * @return TaskInterface
     */
    public function create(string $name, string $description, int $type): TaskInterface;

    /**
     * Update existing task
     * @param int $id
     * @param string $name
     * @param string $description
     * @param int $type
     * @return TaskInterface
     */
    public function update(int $id, string $name, string $description, int $type): TaskInterface;

    /**
     * Delete task
     * @param int $id
     * @param bool $forceDelete
     * @return bool
     */
    public function delete(int $id, bool $forceDelete = false): bool;

    /**
     * Force task deletion
     * @param int $id
     * @return bool
     */
    public function forceDelete(int $id): bool;
}
