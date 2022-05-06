<?php

namespace ConsulConfigManager\Tasks\Events\TaskAction;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Interfaces\UserInterface;

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
     * @param UserInterface|int|null $user
     * @return void
     */
    public function __construct(int $order, UserInterface|int|null $user = null)
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
