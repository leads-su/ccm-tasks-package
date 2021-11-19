<?php

namespace ConsulConfigManager\Tasks\Events\PipelineExecution;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;

/**
 * Class PipelineExecutionDeleted
 * @package ConsulConfigManager\Tasks\Events\PipelineExecution
 */
class PipelineExecutionDeleted extends AbstractEvent
{
    /**
     * PipelineExecutionDeleted constructor.
     * @param UserEntity|int|null $user
     * @return void
     */
    public function __construct(UserEntity|int|null $user = null)
    {
        $this->user = $user;
        parent::__construct();
    }
}
