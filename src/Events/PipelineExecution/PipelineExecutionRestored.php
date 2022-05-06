<?php

namespace ConsulConfigManager\Tasks\Events\PipelineExecution;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class PipelineExecutionRestored
 * @package ConsulConfigManager\Tasks\Events\PipelineExecution
 */
class PipelineExecutionRestored extends AbstractEvent
{
    /**
     * PipelineExecutionRestored constructor.
     * @param UserInterface|int|null $user
     * @return void
     */
    public function __construct(UserInterface|int|null $user = null)
    {
        $this->user = $user;
        parent::__construct();
    }
}
