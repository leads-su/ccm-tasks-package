<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Interface PipelineTaskRepositoryInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface PipelineTaskRepositoryInterface
{
    /**
     * Get list of tasks for specified pipeline
     * @param string|int $pipelineIdentifier
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return Collection
     * @throws BindingResolutionException
     */
    public function list(string|int $pipelineIdentifier, array $with = [], array $append = [], bool $withDeleted = false): Collection;

    /**
     * Get pipeline task information with given data
     * @param string|int $pipelineIdentifier
     * @param string|int $taskIdentifier
     * @param array $with
     * @param array $append
     * @return PipelineTaskInterface
     * @throws BindingResolutionException
     */
    public function get(string|int $pipelineIdentifier, string|int $taskIdentifier, array $with = [], array $append = []): PipelineTaskInterface;

    /**
     * Check whether specified task exists on pipeline
     * @param string|int $pipelineIdentifier
     * @param string|int $taskIdentifier
     * @return bool
     * @throws BindingResolutionException
     */
    public function exists(string|int $pipelineIdentifier, string|int $taskIdentifier): bool;

    /**
     * Get list of all pipeline tasks
     * @param string|int $pipelineIdentifier
     * @return Collection
     * @throws BindingResolutionException
     */
    public function getPipelineTasks(string|int $pipelineIdentifier): Collection;

    /**
     * Create new pipeline task entity
     * @param string|int $pipelineIdentifier
     * @param string|int $taskIdentifier
     * @param int $order
     * @return bool
     * @throws ModelNotFoundException
     * @throws BindingResolutionException
     */
    public function create(string|int $pipelineIdentifier, string|int $taskIdentifier, int $order): bool;

    /**
     * Update existing pipeline task entity
     * @param string|int $pipelineIdentifier
     * @param string|int $taskIdentifier
     * @param int $order
     * @return bool
     * @throws BindingResolutionException
     * @throws ModelNotFoundException
     */
    public function update(string|int $pipelineIdentifier, string|int $taskIdentifier, int $order): bool;

    /**
     * Delete record matching specified values
     * @param string|int $pipelineIdentifier
     * @param string|int $taskIdentifier
     * @param bool $force
     * @return bool
     * @throws BindingResolutionException
     * @throws ModelNotFoundException
     */
    public function delete(string|int $pipelineIdentifier, string|int $taskIdentifier, bool $force = false): bool;

    /**
     * Force delete record
     * @param string|int $pipelineIdentifier
     * @param string|int $taskIdentifier
     * @return bool
     * @throws BindingResolutionException
     * @throws ModelNotFoundException
     */
    public function forceDelete(string|int $pipelineIdentifier, string|int $taskIdentifier): bool;

    /**
     * Restore deleted pipeline task record
     * @param string|int $pipelineIdentifier
     * @param string|int $taskIdentifier
     * @return bool
     * @throws BindingResolutionException
     * @throws ModelNotFoundException
     */
    public function restore(string|int $pipelineIdentifier, string|int $taskIdentifier): bool;

    /**
     * Find pipeline by given task id
     * @param string|int $id
     * @param array $columns
     * @param bool $withDeleted
     * @return PipelineInterface
     * @throws BindingResolutionException
     */
    public function findPipeline(string|int $id, array $columns = ['*'], bool $withDeleted = false): PipelineInterface;

    /**
     * Find task by given task id
     * @param string|int $id
     * @param array $columns
     * @param bool $withDeleted
     * @return TaskInterface
     * @throws BindingResolutionException
     */
    public function findTask(string|int $id, array $columns = ['*'], bool $withDeleted = false): TaskInterface;

    /**
     * Resolve pipeline uuid value
     * @param string|int $id
     * @param bool $withDeleted
     * @return string
     * @throws BindingResolutionException|ModelNotFoundException
     */
    public function resolvePipelineUUID(string|int $id, bool $withDeleted = false): string;

    /**
     * Resolve task uuid value
     * @param string|int $id
     * @param bool $withDeleted
     * @return string
     * @throws BindingResolutionException|ModelNotFoundException
     */
    public function resolveTaskUUID(string|int $id, bool $withDeleted = false): string;

    /**
     * Check if given task bound to given action
     * @param string|int $pipelineIdentifier
     * @param string|int $taskIdentifier
     * @param bool $withDeleted
     * @param bool $return
     * @return bool|PipelineTaskInterface
     * @throws BindingResolutionException
     */
    public function isBound(string|int $pipelineIdentifier, string|int $taskIdentifier, bool $withDeleted = false, bool $return = false): bool|PipelineTaskInterface;

    /**
     * Get PipelineTask uuid
     * @param string|int $pipelineIdentifier
     * @param string|int $taskIdentifier
     * @param bool $withDeleted
     * @return string
     * @throws BindingResolutionException
     */
    public function pipelineTaskUUID(string|int $pipelineIdentifier, string|int $taskIdentifier, bool $withDeleted = false): string;

    /**
     * Check whether specified pipeline exists
     * @param string $pipelineIdentifier
     * @return bool
     * @throws BindingResolutionException
     */
    public function pipelineExists(string $pipelineIdentifier): bool;

    /**
     * Check whether specified task exists
     * @param string $taskIdentifier
     * @return bool
     * @throws BindingResolutionException
     */
    public function taskExists(string $taskIdentifier): bool;
}
