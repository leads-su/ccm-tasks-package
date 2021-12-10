<?php

namespace ConsulConfigManager\Tasks\Events\Task;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;

/**
 * Class TaskRestored
 * @package ConsulConfigManager\Tasks\Events\Task
 */
class TaskRestored extends AbstractEvent
{
    /**
     * TaskRestored constructor.
     * @param UserEntity|int|null $user
     * @return void
     */
    public function __construct(UserEntity|int|null $user = null)
    {
        $this->user = $user;
        parent::__construct();
    }
}
