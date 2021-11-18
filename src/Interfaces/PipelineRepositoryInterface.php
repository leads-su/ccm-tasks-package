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
     *
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Find pipeline
     * @param int $id
     * @param array|string[] $columns
     * @return PipelineInterface|null
     */
    public function find(int $id, array $columns = ['*']): PipelineInterface|null;

    /**
     * Find pipeline or fail and throw exception
     * @param int $id
     * @param array|string[] $columns
     * @return PipelineInterface
     * @throws ModelNotFoundException
     */
    public function findOrFail(int $id, array $columns = ['*']): PipelineInterface;

    /**
     * Find pipeline by specified field
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @return PipelineInterface|null
     */
    public function findBy(string $field, string $value, array $columns = ['*']): PipelineInterface|null;

    /**
     * Find pipeline by specified field or throw exception
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @throws ModelNotFoundException
     * @return PipelineInterface
     */
    public function findByOrFail(string $field, string $value, array $columns = ['*']): PipelineInterface;

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
     * Force pipeline deletion
     * @param int $id
     * @return bool
     */
    public function forceDelete(int $id): bool;
}
