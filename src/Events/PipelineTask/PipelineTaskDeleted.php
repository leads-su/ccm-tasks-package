<?php

namespace ConsulConfigManager\Tasks\Events\PipelineTask;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class PipelineTaskDeleted
 * @package ConsulConfigManager\Tasks\Events\PipelineTask
 */
class PipelineTaskDeleted extends AbstractEvent
{
    /**
     * Indicates whether record should be force deleted
     * @var bool
     */
    private bool $force;

    /**
     * PipelineTaskDeleted constructor.
     * @param bool $force
     * @param UserInterface|int|null $user
     */
    public function __construct(bool $force = false, UserInterface|int|null $user = null)
    {
        $this->force = $force;
        $this->user = $user;
        parent::__construct();
    }

    /**
     * Indicates whether record should be force deleted
     * @return bool
     */
    public function isForced(): bool
    {
        return $this->force;
    }
}
