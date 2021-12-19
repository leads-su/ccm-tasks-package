<?php

namespace ConsulConfigManager\Tasks\Events\PipelineTask;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;

/**
 * Class PipelineTaskRestored
 * @package ConsulConfigManager\Tasks\Events\PipelineTask
 */
class PipelineTaskRestored extends AbstractEvent
{
    /**
     * PipelineTaskRestored constructor.
     * @param UserEntity|int|null $user
     * @return void
     */
    public function __construct(UserEntity|int|null $user = null)
    {
        $this->user = $user;
        parent::__construct();
    }
}
