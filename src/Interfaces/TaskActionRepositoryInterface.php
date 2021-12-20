<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Interface TaskActionRepositoryInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface TaskActionRepositoryInterface
{
    /**
     * Get list of actions for specified task
     * @param string|int $taskIdentifier
     * @param array $with
     * @param array $append
     * @param bool $withDeleted
     * @return Collection
     * @throws BindingResolutionException
     */
    public function list(string|int $taskIdentifier, array $with = [], array $append = [], bool $withDeleted = false): Collection;

    /**
     * Get task action information with given data
     * @param string|int $taskIdentifier
     * @param string|int $actionIdentifier
     * @param array $with
     * @param array $append
     * @return TaskActionInterface
     * @throws BindingResolutionException
     */
    public function get(string|int $taskIdentifier, string|int $actionIdentifier, array $with = [], array $append = []): TaskActionInterface;

    /**
     * Create new task action entity
     * @param string|int $taskIdentifier
     * @param string|int $actionIdentifier
     * @param int $order
     * @return bool
     * @throws ModelNotFoundException
     * @throws BindingResolutionException
     */
    public function create(string|int $taskIdentifier, string|int $actionIdentifier, int $order): bool;

    /**
     * Update existing task action entity
     * @param string|int $taskIdentifier
     * @param string|int $actionIdentifier
     * @param int $order
     * @return bool
     * @throws BindingResolutionException
     * @throws ModelNotFoundException
     */
    public function update(string|int $taskIdentifier, string|int $actionIdentifier, int $order): bool;

    /**
     * Delete record matching specified values
     * @param string|int $taskIdentifier
     * @param string|int $actionIdentifier
     * @param bool $force
     * @return bool
     * @throws BindingResolutionException
     * @throws ModelNotFoundException
     */
    public function delete(string|int $taskIdentifier, string|int $actionIdentifier, bool $force = false): bool;

    /**
     * Force delete record
     * @param string|int $taskIdentifier
     * @param string|int $actionIdentifier
     * @return bool
     * @throws BindingResolutionException
     * @throws ModelNotFoundException
     */
    public function forceDelete(string|int $taskIdentifier, string|int $actionIdentifier): bool;

    /**
     * Restore deleted task action record
     * @param string|int $taskIdentifier
     * @param string|int $actionIdentifier
     * @return bool
     * @throws BindingResolutionException
     * @throws ModelNotFoundException
     */
    public function restore(string|int $taskIdentifier, string|int $actionIdentifier): bool;

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
     * Find action by given task id
     * @param string|int $id
     * @param array $columns
     * @param bool $withDeleted
     * @return ActionInterface
     * @throws BindingResolutionException
     */
    public function findAction(string|int $id, array $columns = ['*'], bool $withDeleted = false): ActionInterface;

    /**
     * Resolve task uuid value
     * @param string|int $id
     * @param bool $withDeleted
     * @return string
     * @throws BindingResolutionException|ModelNotFoundException
     */
    public function resolveTaskUUID(string|int $id, bool $withDeleted = false): string;

    /**
     * Resolve action uuid value
     * @param string|int $id
     * @param bool $withDeleted
     * @return string
     * @throws BindingResolutionException|ModelNotFoundException
     */
    public function resolveActionUUID(string|int $id, bool $withDeleted = false): string;

    /**
     * Check if given task bound to given action
     * @param string|int $taskID
     * @param string|int $actionID
     * @param bool $withDeleted
     * @param bool $return
     * @return bool|TaskActionInterface
     * @throws BindingResolutionException
     */
    public function isBound(string|int $taskID, string|int $actionID, bool $withDeleted = false, bool $return = false): bool|TaskActionInterface;

    /**
     * Get TaskAction uuid
     * @param string|int $taskID
     * @param string|int $actionID
     * @param bool $withDeleted
     * @return string
     * @throws BindingResolutionException
     */
    public function taskActionUUID(string|int $taskID, string|int $actionID, bool $withDeleted = false): string;

    /**
     * Check whether specified task exists
     * @param string $taskID
     * @return bool
     * @throws BindingResolutionException
     */
    public function taskExists(string $taskID): bool;

    /**
     * Check whether specified action exists
     * @param string $actionID
     * @return bool
     * @throws BindingResolutionException
     */
    public function actionExists(string $actionID): bool;
}
