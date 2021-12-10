<?php

namespace ConsulConfigManager\Tasks\Events\Action;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;

/**
 * Class ActionRestored
 * @package ConsulConfigManager\Tasks\Events\Action
 */
class ActionRestored extends AbstractEvent
{
    /**
     * ActionRestored constructor.
     * @param UserEntity|int|null $user
     * @return void
     */
    public function __construct(UserEntity|int|null $user = null)
    {
        $this->user = $user;
        parent::__construct();
    }
}
