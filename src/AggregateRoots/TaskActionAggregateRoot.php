<?php

namespace ConsulConfigManager\Tasks\AggregateRoots;

use ConsulConfigManager\Tasks\Events;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;

/**
 * Class TaskActionAggregateRoot
 * @package ConsulConfigManager\Tasks\AggregateRoots
 */
class TaskActionAggregateRoot extends AggregateRoot
{
    /**
     * Handle `create` event
     * @param string $task
     * @param string $action
     * @param int $order
     * @param UserEntity|int|null $user
     * @return TaskActionAggregateRoot
     */
    public function createEntity(string $task, string $action, int $order, UserEntity|int|null $user = null): TaskActionAggregateRoot
    {
        $this->recordThat(new Events\TaskAction\TaskActionCreated(
            $task,
            $action,
            $order,
            $user,
        ));
        return $this;
    }

    /**
     * Handle `update` event
     * @param int $order
     * @param UserEntity|int|null $user
     * @return TaskActionAggregateRoot
     */
    public function updateEntity(int $order, UserEntity|int|null $user = null): TaskActionAggregateRoot
    {
        $this->recordThat(new Events\TaskAction\TaskActionUpdated(
            $order,
            $user,
        ));
        return $this;
    }

    /**
     * Handle `delete` event
     * @param bool $force
     * @param UserEntity|int|null $user
     * @return TaskActionAggregateRoot
     */
    public function deleteEntity(bool $force = false, UserEntity|int|null $user = null): TaskActionAggregateRoot
    {
        $this->recordThat(new Events\TaskAction\TaskActionDeleted($force, $user));
        return $this;
    }

    /**
     * Handle `restore` event
     * @param UserEntity|int|null $user
     * @return TaskActionAggregateRoot
     */
    public function restoreEntity(UserEntity|int|null $user = null): TaskActionAggregateRoot
    {
        $this->recordThat(new Events\TaskAction\TaskActionRestored($user));
        return $this;
    }
}
