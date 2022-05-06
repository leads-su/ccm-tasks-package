<?php

namespace ConsulConfigManager\Tasks\Events\Task;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class TaskDeleted
 * @package ConsulConfigManager\Tasks\Events\Task
 */
class TaskDeleted extends AbstractEvent
{
    /**
     * TaskDeleted constructor.
     * @param UserInterface|int|null $user
     * @return void
     */
    public function __construct(UserInterface|int|null $user = null)
    {
        $this->user = $user;
        parent::__construct();
    }
}
