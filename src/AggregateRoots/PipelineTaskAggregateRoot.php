<?php

namespace ConsulConfigManager\Tasks\AggregateRoots;

use ConsulConfigManager\Tasks\Events;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class PipelineTaskAggregateRoot
 * @package ConsulConfigManager\Tasks\AggregateRoots
 */
class PipelineTaskAggregateRoot extends AggregateRoot
{
    /**
     * Handle `create` event
     * @param string $pipeline
     * @param string $task
     * @param int $order
     * @param UserInterface|int|null $user
     * @return PipelineTaskAggregateRoot
     */
    public function createEntity(string $pipeline, string $task, int $order, UserInterface|int|null $user = null): PipelineTaskAggregateRoot
    {
        $this->recordThat(new Events\PipelineTask\PipelineTaskCreated(
            $pipeline,
            $task,
            $order,
            $user,
        ));
        return $this;
    }

    /**
     * Handle `update` event
     * @param int $order
     * @param UserInterface|int|null $user
     * @return PipelineTaskAggregateRoot
     */
    public function updateEntity(int $order, UserInterface|int|null $user = null): PipelineTaskAggregateRoot
    {
        $this->recordThat(new Events\PipelineTask\PipelineTaskUpdated(
            $order,
            $user,
        ));
        return $this;
    }

    /**
     * Handle `delete` event
     * @param bool $force
     * @param UserInterface|int|null $user
     * @return PipelineTaskAggregateRoot
     */
    public function deleteEntity(bool $force = false, UserInterface|int|null $user = null): PipelineTaskAggregateRoot
    {
        $this->recordThat(new Events\PipelineTask\PipelineTaskDeleted($force, $user));
        return $this;
    }

    /**
     * Handle `restore` event
     * @param UserInterface|int|null $user
     * @return PipelineTaskAggregateRoot
     */
    public function restoreEntity(UserInterface|int|null $user = null): PipelineTaskAggregateRoot
    {
        $this->recordThat(new Events\PipelineTask\PipelineTaskRestored($user));
        return $this;
    }
}
