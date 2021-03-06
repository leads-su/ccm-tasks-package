<?php

namespace ConsulConfigManager\Tasks\AggregateRoots;

use ConsulConfigManager\Tasks\Events;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class TaskAggregateRoot
 * @package ConsulConfigManager\Tasks\AggregateRoots
 */
class TaskAggregateRoot extends AggregateRoot
{
    /**
     * Handle `create` event
     * @param string $name
     * @param string $description
     * @param bool $failOnError
     * @param UserInterface|int|null $user
     * @return $this
     */
    public function createEntity(string $name, string $description, bool $failOnError = false, UserInterface|int|null $user = null): TaskAggregateRoot
    {
        $this->recordThat(new Events\Task\TaskCreated(
            $name,
            $description,
            $failOnError,
            $user,
        ));
        return $this;
    }

    /**
     * Handle `update` event
     * @param string $name
     * @param string $description
     * @param bool $failOnError
     * @param UserInterface|int|null $user
     * @return $this
     */
    public function updateEntity(string $name, string $description, bool $failOnError = false, UserInterface|int|null $user = null): TaskAggregateRoot
    {
        $this->recordThat(new Events\Task\TaskUpdated(
            $name,
            $description,
            $failOnError,
            $user,
        ));
        return $this;
    }

    /**
     * Handle `delete` event
     * @param UserInterface|int|null $user
     * @return $this
     */
    public function deleteEntity(UserInterface|int|null $user = null): TaskAggregateRoot
    {
        $this->recordThat(new Events\Task\TaskDeleted(
            $user,
        ));
        return $this;
    }

    /**
     * Handle `restore` event
     * @param UserInterface|int|null $user
     * @return $this
     */
    public function restoreEntity(UserInterface|int|null $user = null): TaskAggregateRoot
    {
        $this->recordThat(new Events\Task\TaskRestored(
            $user,
        ));
        return $this;
    }
}
