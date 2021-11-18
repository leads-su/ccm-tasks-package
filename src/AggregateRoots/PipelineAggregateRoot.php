<?php

namespace ConsulConfigManager\Tasks\AggregateRoots;

use ConsulConfigManager\Tasks\Events;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;

/**
 * Class PipelineAggregateRoot
 * @package ConsulConfigManager\Tasks\AggregateRoots
 */
class PipelineAggregateRoot extends AggregateRoot
{
    /**
     * Handle `create` event
     * @param string $name
     * @param string $description
     * @param UserEntity|int|null $user
     * @return $this
     */
    public function createEntity(string $name, string $description, UserEntity|int|null $user = null): PipelineAggregateRoot
    {
        $this->recordThat(new Events\Pipeline\PipelineCreated(
            $name,
            $description,
            $user,
        ));
        return $this;
    }

    /**
     * Handle `update` event
     * @param string $name
     * @param string $description
     * @param UserEntity|int|null $user
     * @return $this
     */
    public function updateEntity(string $name, string $description, UserEntity|int|null $user = null): PipelineAggregateRoot
    {
        $this->recordThat(new Events\Pipeline\PipelineUpdated(
            $name,
            $description,
            $user,
        ));
        return $this;
    }

    /**
     * Handle `delete` event
     * @param UserEntity|int|null $user
     * @return $this
     */
    public function deleteEntity(UserEntity|int|null $user = null): PipelineAggregateRoot
    {
        $this->recordThat(new Events\Pipeline\PipelineDeleted(
            $user,
        ));
        return $this;
    }
}
