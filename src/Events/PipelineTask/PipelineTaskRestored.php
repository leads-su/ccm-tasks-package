<?php

namespace ConsulConfigManager\Tasks\Events\PipelineTask;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class PipelineTaskRestored
 * @package ConsulConfigManager\Tasks\Events\PipelineTask
 */
class PipelineTaskRestored extends AbstractEvent
{
    /**
     * PipelineTaskRestored constructor.
     * @param UserInterface|int|null $user
     * @return void
     */
    public function __construct(UserInterface|int|null $user = null)
    {
        $this->user = $user;
        parent::__construct();
    }
}
