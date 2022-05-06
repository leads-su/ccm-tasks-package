<?php

namespace ConsulConfigManager\Tasks\AggregateRoots;

use ConsulConfigManager\Tasks\Events;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class PipelineExecutionAggregateRoot
 * @package ConsulConfigManager\Tasks\AggregateRoots
 */
class PipelineExecutionAggregateRoot extends AggregateRoot
{
    /**
     * Handle `created` event
     * @param string $pipelineUuid
     * @param int $state
     * @param UserInterface|int|null $user
     * @return $this
     */
    public function createEntity(string $pipelineUuid, int $state, UserInterface|int|null $user = null): PipelineExecutionAggregateRoot
    {
        $this->recordThat(new Events\PipelineExecution\PipelineExecutionCreated(
            $pipelineUuid,
            $state,
            $user,
        ));
        return $this;
    }

    /**
     * Handle `updated` event
     * @param string $pipelineUuid
     * @param int $state
     * @param UserInterface|int|null $user
     * @return $this
     */
    public function updateEntity(string $pipelineUuid, int $state, UserInterface|int|null $user = null): PipelineExecutionAggregateRoot
    {
        $this->recordThat(new Events\PipelineExecution\PipelineExecutionUpdated(
            $pipelineUuid,
            $state,
            $user,
        ));
        return $this;
    }

    /**
     * Handle `deleted` event
     * @param UserInterface|int|null $user
     * @return $this
     */
    public function deleteEntity(UserInterface|int|null $user = null): PipelineExecutionAggregateRoot
    {
        $this->recordThat(new Events\PipelineExecution\PipelineExecutionDeleted(
            $user,
        ));
        return $this;
    }

    /**
     * Handle `restored` event
     * @param UserInterface|int|null $user
     * @return $this
     */
    public function restoreEntity(UserInterface|int|null $user = null): PipelineExecutionAggregateRoot
    {
        $this->recordThat(new Events\PipelineExecution\PipelineExecutionRestored(
            $user,
        ));
        return $this;
    }
}
