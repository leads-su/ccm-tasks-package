<?php

namespace ConsulConfigManager\Tasks\Events\Action;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;

/**
 * Class ActionDeleted
 * @package ConsulConfigManager\Tasks\Events\Action
 */
class ActionDeleted extends AbstractEvent
{
    /**
     * ActionDeleted constructor.
     * @param UserEntity|int|null $user
     * @return void
     */
    public function __construct(UserEntity|int|null $user = null)
    {
        $this->user = $user;
        parent::__construct();
    }
}
