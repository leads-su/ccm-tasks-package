<?php

namespace ConsulConfigManager\Tasks\Events\PipelineTask;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;

/**
 * Class PipelineTaskCreated
 * @package ConsulConfigManager\Tasks\Events\PipelineTask
 */
class PipelineTaskCreated extends AbstractEvent
{
    /**
     * Pipeline uuid reference
     * @var string
     */
    private string $pipeline;

    /**
     * Task uuid reference
     * @var string
     */
    private string $task;

    /**
     * Order reference
     * @var int
     */
    private int $order;

    /**
     * PipelineTaskCreated constructor.
     * @param string $pipeline
     * @param string $task
     * @param int $order
     * @param UserEntity|int|null $user
     */
    public function __construct(string $pipeline, string $task, int $order, UserEntity|int|null $user = null)
    {
        $this->pipeline = $pipeline;
        $this->task = $task;
        $this->order = $order;
        $this->user = $user;
        parent::__construct();
    }

    /**
     * Get pipeline
     * @return string
     */
    public function getPipeline(): string
    {
        return $this->pipeline;
    }

    /**
     * Get task
     * @return string
     */
    public function getTask(): string
    {
        return $this->task;
    }

    /**
     * Get order
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }
}
