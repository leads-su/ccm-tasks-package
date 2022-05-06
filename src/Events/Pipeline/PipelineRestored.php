<?php

namespace ConsulConfigManager\Tasks\Events\Pipeline;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class PipelineRestored
 * @package ConsulConfigManager\Tasks\Events\Pipeline
 */
class PipelineRestored extends AbstractEvent
{
    /**
     * PipelineRestored constructor.
     * @param UserInterface|int|null $user
     * @return void
     */
    public function __construct(UserInterface|int|null $user = null)
    {
        $this->user = $user;
        parent::__construct();
    }
}
