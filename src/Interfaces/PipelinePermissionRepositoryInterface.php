<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use ConsulConfigManager\Users\Interfaces\UserInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Exceptions\ModelAlreadyExistsException;

/**
 * Interface PipelinePermissionRepositoryInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface PipelinePermissionRepositoryInterface
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
     * @return PipelinePermissionInterface|null
     */
    public function find(int $id, array $columns = ['*'], array $with = [], array $append = []): PipelinePermissionInterface|null;

    /**
     * Find entity by primary key or fail and throw exception
     * @param int $id
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @return PipelinePermissionInterface
     * @throws ModelNotFoundException
     */
    public function findOrFail(int $id, array $columns = ['*'], array $with = [], array $append = []): PipelinePermissionInterface;

    /**
     * Find entity by specified field
     * @param string $field
     * @param string $value
     * @param array|string[] $columns
     * @param array $with
     * @param array $append
     * @return PipelinePermissionInterface|null
     */
    public function findBy(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = []): PipelinePermissionInterface|null;

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
     * @return PipelinePermissionInterface
     * @throws ModelNotFoundException
     */
    public function findByOrFail(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = []): PipelinePermissionInterface;

    /**
     * Check whether user has permission to work with specified pipeline
     * @param string $pipelineIdentifier
     * @param UserInterface|int|null $user
     * @return bool
     */
    public function hasPermission(string $pipelineIdentifier, UserInterface|int|null $user = null): bool;

    /**
     * Check whether user already has permission to work with specified pipeline
     * @param int $userIdentifier
     * @param string $pipelineIdentifier
     * @return bool
     */
    public function permissionExists(int $userIdentifier, string $pipelineIdentifier): bool;

    /**
     * Create new permission
     * @param int $userIdentifier
     * @param string $pipelineIdentifier
     * @throws ModelAlreadyExistsException
     * @return PipelinePermissionInterface
     */
    public function create(int $userIdentifier, string $pipelineIdentifier): PipelinePermissionInterface;

    /**
     * Delete existing permission
     * @param int $userIdentifier
     * @param string $pipelineIdentifier
     * @param bool $forceDelete
     * @throws ModelNotFoundException
     * @return bool
     */
    public function delete(int $userIdentifier, string $pipelineIdentifier, bool $forceDelete = false): bool;

    /**
     * Force delete existing permission
     * @param int $userIdentifier
     * @param string $pipelineIdentifier
     * @throws ModelNotFoundException
     * @return bool
     */
    public function forceDelete(int $userIdentifier, string $pipelineIdentifier): bool;
}
