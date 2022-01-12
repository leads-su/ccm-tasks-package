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
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return Collection
     */
    public function all(array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): Collection;

    /**
     * Find task
     * @param int $id
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return TaskInterface|null
     */
    public function find(int $id, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): TaskInterface|null;

    /**
     * Find task or fail and throw exception
     * @param int $id
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return TaskInterface
     * @throws ModelNotFoundException
     */
    public function findOrFail(int $id, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): TaskInterface;

    /**
     * Find task by specified field
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return TaskInterface|null
     */
    public function findBy(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): TaskInterface|null;

    /**
     * Find task by specified field or throw exception
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return TaskInterface
     * @throws ModelNotFoundException
     */
    public function findByOrFail(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): TaskInterface;

    /**
     * Find entity while using multiple fields to perform search
     * @param array $fields
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return TaskInterface|null
     */
    public function findByMany(array $fields, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): TaskInterface|null;

    /**
     * Find entity while using multiple fields to perform search or throw exception
     * @param array $fields
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return TaskInterface
     * @throws ModelNotFoundException
     */
    public function findByManyOrFail(array $fields, mixed $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): TaskInterface;

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
     * Restore task
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool;

    /**
     * Force task deletion
     * @param int $id
     * @return bool
     */
    public function forceDelete(int $id): bool;
}
