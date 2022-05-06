<?php

namespace ConsulConfigManager\Tasks\AggregateRoots;

use ConsulConfigManager\Tasks\Events;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use ConsulConfigManager\Users\Interfaces\UserInterface;

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
     * @param UserInterface|int|null $user
     * @return TaskActionAggregateRoot
     */
    public function createEntity(string $task, string $action, int $order, UserInterface|int|null $user = null): TaskActionAggregateRoot
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
     * @param UserInterface|int|null $user
     * @return TaskActionAggregateRoot
     */
    public function updateEntity(int $order, UserInterface|int|null $user = null): TaskActionAggregateRoot
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
     * @param UserInterface|int|null $user
     * @return TaskActionAggregateRoot
     */
    public function deleteEntity(bool $force = false, UserInterface|int|null $user = null): TaskActionAggregateRoot
    {
        $this->recordThat(new Events\TaskAction\TaskActionDeleted($force, $user));
        return $this;
    }

    /**
     * Handle `restore` event
     * @param UserInterface|int|null $user
     * @return TaskActionAggregateRoot
     */
    public function restoreEntity(UserInterface|int|null $user = null): TaskActionAggregateRoot
    {
        $this->recordThat(new Events\TaskAction\TaskActionRestored($user));
        return $this;
    }
}
