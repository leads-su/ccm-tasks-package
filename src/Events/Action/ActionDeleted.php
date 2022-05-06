<?php

namespace ConsulConfigManager\Tasks\Events\Action;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class ActionDeleted
 * @package ConsulConfigManager\Tasks\Events\Action
 */
class ActionDeleted extends AbstractEvent
{
    /**
     * ActionDeleted constructor.
     * @param UserInterface|int|null $user
     * @return void
     */
    public function __construct(UserInterface|int|null $user = null)
    {
        $this->user = $user;
        parent::__construct();
    }
}
