<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Update;

use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;

/**
 * Class TaskActionUpdateResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Update
 */
class TaskActionUpdateResponseModel
{
    /**
     * Task instance
     * @var TaskInterface|null
     */
    private ?TaskInterface $task;

    /**
     * Action instance
     * @var ActionInterface|null
     */
    private ?ActionInterface $action;

    /**
     * TaskActionUpdateResponseModel constructor.
     * @param TaskInterface|null $task
     * @param ActionInterface|null $action
     * @return void
     */
    public function __construct(?TaskInterface $task = null, ?ActionInterface $action = null)
    {
        $this->task = $task;
        $this->action = $action;
    }

    /**
     * Get task instance
     * @return TaskInterface|null
     */
    public function getTask(): ?TaskInterface
    {
        return $this->task;
    }

    /**
     * Get action instance
     * @return ActionInterface|null
     */
    public function getAction(): ?ActionInterface
    {
        return $this->action;
    }
}
