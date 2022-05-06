<?php

namespace ConsulConfigManager\Tasks\AggregateRoots;

use ConsulConfigManager\Tasks\Events;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class ActionAggregateRoot
 * @package ConsulConfigManager\Tasks\AggregateRoots
 */
class ActionAggregateRoot extends AggregateRoot
{
    /**
     * Handle `create` event
     * @param string $name
     * @param string $description
     * @param int $type
     * @param string $command
     * @param array $arguments
     * @param string|null $workingDirectory
     * @param string|null $runAs
     * @param bool $useSudo
     * @param bool $failOnError
     * @param UserInterface|int|null $user
     * @return $this
     */
    public function createEntity(
        string $name,
        string $description,
        int $type,
        string $command,
        array $arguments,
        ?string $workingDirectory = null,
        ?string $runAs = null,
        bool $useSudo = false,
        bool $failOnError = true,
        UserInterface|int|null $user = null,
    ): ActionAggregateRoot {
        $this->recordThat(new Events\Action\ActionCreated(
            $name,
            $description,
            $type,
            $command,
            $arguments,
            $workingDirectory,
            $runAs,
            $useSudo,
            $failOnError,
            $user,
        ));
        return $this;
    }

    /**
     * Handle `update` event
     * @param string $name
     * @param string $description
     * @param int $type
     * @param string $command
     * @param array $arguments
     * @param string|null $workingDirectory
     * @param string|null $runAs
     * @param bool $useSudo
     * @param bool $failOnError
     * @param UserInterface|int|null $user
     * @return $this
     */
    public function updateEntity(
        string $name,
        string $description,
        int $type,
        string $command,
        array $arguments,
        ?string $workingDirectory = null,
        ?string $runAs = null,
        bool $useSudo = false,
        bool $failOnError = true,
        UserInterface|int|null $user = null,
    ): ActionAggregateRoot {
        $this->recordThat(new Events\Action\ActionUpdated(
            $name,
            $description,
            $type,
            $command,
            $arguments,
            $workingDirectory,
            $runAs,
            $useSudo,
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
    public function deleteEntity(UserInterface|int|null $user = null): ActionAggregateRoot
    {
        $this->recordThat(new Events\Action\ActionDeleted($user));
        return $this;
    }

    /**
     * Handle `restore` event
     * @param UserInterface|int|null $user
     * @return $this
     */
    public function restoreEntity(UserInterface|int|null $user = null): ActionAggregateRoot
    {
        $this->recordThat(new Events\Action\ActionRestored($user));
        return $this;
    }
}
