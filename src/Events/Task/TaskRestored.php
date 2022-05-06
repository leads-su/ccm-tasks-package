<?php

namespace ConsulConfigManager\Tasks\Events\Task;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class TaskRestored
 * @package ConsulConfigManager\Tasks\Events\Task
 */
class TaskRestored extends AbstractEvent
{
    /**
     * TaskRestored constructor.
     * @param UserInterface|int|null $user
     * @return void
     */
    public function __construct(UserInterface|int|null $user = null)
    {
        $this->user = $user;
        parent::__construct();
    }
}
