<?php

namespace ConsulConfigManager\Tasks\Events\TaskAction;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class TaskActionCreated
 * @package ConsulConfigManager\Tasks\Events\TaskAction
 */
class TaskActionCreated extends AbstractEvent
{
    /**
     * Task uuid reference
     * @var string
     */
    private string $task;

    /**
     * Action uuid reference
     * @var string
     */
    private string $action;

    /**
     * Order reference
     * @var int
     */
    private int $order;

    /**
     * TaskActionCreated constructor.
     * @param string $task
     * @param string $action
     * @param int $order
     * @param UserInterface|int|null $user
     * @return void
     */
    public function __construct(string $task, string $action, int $order, UserInterface|int|null $user = null)
    {
        $this->task = $task;
        $this->action = $action;
        $this->order = $order;
        $this->user = $user;
        parent::__construct();
    }

    /**
     * Get task
     * @return string
     */
    public function getTask(): string
    {
        return $this->task;
    }

    /**
     * Get action
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
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
