<?php

namespace ConsulConfigManager\Tasks\Events\Task;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;

/**
 * Class TaskDeleted
 * @package ConsulConfigManager\Tasks\Events\Task
 */
class TaskDeleted extends AbstractEvent
{
    /**
     * TaskDeleted constructor.
     * @param UserEntity|int|null $user
     * @return void
     */
    public function __construct(UserEntity|int|null $user = null)
    {
        $this->user = $user;
        parent::__construct();
    }
}
