<?php

namespace ConsulConfigManager\Tasks\Events\Action;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class ActionRestored
 * @package ConsulConfigManager\Tasks\Events\Action
 */
class ActionRestored extends AbstractEvent
{
    /**
     * ActionRestored constructor.
     * @param UserInterface|int|null $user
     * @return void
     */
    public function __construct(UserInterface|int|null $user = null)
    {
        $this->user = $user;
        parent::__construct();
    }
}
