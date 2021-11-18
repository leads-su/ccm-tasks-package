<?php

namespace ConsulConfigManager\Tasks\Events\Pipeline;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;

/**
 * Class PipelineDeleted
 * @package ConsulConfigManager\Tasks\Events\Pipeline
 */
class PipelineDeleted extends AbstractEvent
{
    /**
     * PipelineDeleted constructor.
     * @param UserEntity|int|null $user
     * @return void
     */
    public function __construct(UserEntity|int|null $user = null)
    {
        $this->user = $user;
        parent::__construct();
    }
}
