<?php

namespace ConsulConfigManager\Tasks\Events\TaskAction;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;

/**
 * Class TaskActionUpdated
 * @package ConsulConfigManager\Tasks\Events\TaskAction
 */
class TaskActionUpdated extends AbstractEvent
{
    /**
     * Order reference
     * @var int
     */
    private int $order;

    /**
     * TaskActionUpdated constructor.
     * @param int $order
     * @param UserEntity|int|null $user
     * @return void
     */
    public function __construct(int $order, UserEntity|int|null $user = null)
    {
        $this->order = $order;
        $this->user = $user;
        parent::__construct();
    }

    /**
     * Get order
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }
}
