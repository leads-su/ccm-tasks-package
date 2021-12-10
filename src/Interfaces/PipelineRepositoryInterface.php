<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface PipelineRepositoryInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface PipelineRepositoryInterface
{
    /**
     * Get list of all entries from database
     * @param array|string[] $columns
     * @param bool $withDeleted
     * @return Collection
     */
    public function all(array $columns = ['*'], bool $withDeleted = false): Collection;

    /**
     * Find pipeline
     * @param int $id
     * @param array|string[] $columns
     * @param bool $withDeleted
     * @return PipelineInterface|null
     */
    public function find(int $id, array $columns = ['*'], bool $withDeleted = false): PipelineInterface|null;

    /**
     * Find pipeline or fail and throw exception
     * @param int $id
     * @param array|string[] $columns
     * @param bool $withDeleted
     * @return PipelineInterface
     * @throws ModelNotFoundException
     */
    public function findOrFail(int $id, array $columns = ['*'], bool $withDeleted = false): PipelineInterface;

    /**
     * Find pipeline by specified field
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @param bool $withDeleted
     * @return PipelineInterface|null
     */
    public function findBy(string $field, string $value, array $columns = ['*'], bool $withDeleted = false): PipelineInterface|null;

    /**
     * Find pipeline by specified field or throw exception
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @param bool $withDeleted
     * @return PipelineInterface
     * @throws ModelNotFoundException
     */
    public function findByOrFail(string $field, string $value, array $columns = ['*'], bool $withDeleted = false): PipelineInterface;

    /**
     * Find entity while using multiple fields to perform search
     * @param array $fields
     * @param string $value
     * @param array|string[] $columns
     * @param bool $withDeleted
     * @return PipelineInterface|null
     */
    public function findByMany(array $fields, string $value, array $columns = ['*'], bool $withDeleted = false): PipelineInterface|null;

    /**
     * Find entity while using multiple fields to perform search or throw exception
     * @param array $fields
     * @param string $value
     * @param array|string[] $columns
     * @param bool $withDeleted
     * @return PipelineInterface
     * @throws ModelNotFoundException
     */
    public function findByManyOrFail(array $fields, string $value, array $columns = ['*'], bool $withDeleted = false): PipelineInterface;

    /**
     * Create new pipeline
     * @param string $name
     * @param string $description
     * @return PipelineInterface
     */
    public function create(string $name, string $description): PipelineInterface;

    /**
     * Update existing pipeline
     * @param int $id
     * @param string $name
     * @param string $description
     * @return PipelineInterface
     */
    public function update(int $id, string $name, string $description): PipelineInterface;

    /**
     * Delete pipeline
     * @param int $id
     * @param bool $forceDelete
     * @return bool
     */
    public function delete(int $id, bool $forceDelete = false): bool;

    /**
     * Restore pipeline
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool;

    /**
     * Force pipeline deletion
     * @param int $id
     * @return bool
     */
    public function forceDelete(int $id): bool;
}
