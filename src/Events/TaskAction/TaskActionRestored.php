<?php

namespace ConsulConfigManager\Tasks\Events\TaskAction;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class TaskActionRestored
 * @package ConsulConfigManager\Tasks\Events\TaskAction
 */
class TaskActionRestored extends AbstractEvent
{
    /**
     * TaskActionRestored constructor.
     * @param UserInterface|int|null $user
     * @return void
     */
    public function __construct(UserInterface|int|null $user = null)
    {
        $this->user = $user;
        parent::__construct();
    }
}
