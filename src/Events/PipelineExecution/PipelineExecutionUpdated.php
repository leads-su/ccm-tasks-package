<?php

namespace ConsulConfigManager\Tasks\Events\PipelineExecution;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class PipelineExecutionUpdated
 * @package ConsulConfigManager\Tasks\Events\PipelineExecution
 */
class PipelineExecutionUpdated extends AbstractEvent
{
    /**
     * Pipeline execution pipeline uuid
     * @var string
     */
    private string $pipelineUuid;

    /**
     * Pipeline execution state
     * @var int
     */
    private int $state;

    /**
     * PipelineExecutionUpdated constructor.
     * @param string $pipelineUuid
     * @param int $state
     * @param UserInterface|int|null $user
     * @return void
     */
    public function __construct(string $pipelineUuid, int $state, UserInterface|int|null $user = null)
    {
        $this->pipelineUuid = $pipelineUuid;
        $this->state = $state;
        $this->user = $user;
        parent::__construct();
    }

    /**
     * Get pipeline execution pipeline uuid
     * @return string
     */
    public function getPipelineUuid(): string
    {
        return $this->pipelineUuid;
    }

    /**
     * Get pipeline execution state
     * @return int
     */
    public function getState(): int
    {
        return $this->state;
    }
}
