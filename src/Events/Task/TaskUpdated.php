<?php

namespace ConsulConfigManager\Tasks\Events\Task;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class TaskUpdated
 * @package ConsulConfigManager\Tasks\Events\Task
 */
class TaskUpdated extends AbstractEvent
{
    /**
     * Task name
     * @var string
     */
    private string $name;

    /**
     * Task description
     * @var string
     */
    private string $description;

    /**
     * Fail task on error
     * @var bool
     */
    private bool $failOnError;

    /**
     * TaskUpdated constructor.
     * @param string $name
     * @param string $description
     * @param bool $failOnError
     * @param UserInterface|int|null $user
     */
    public function __construct(
        string $name,
        string $description,
        bool $failOnError = false,
        UserInterface|int|null $user = null,
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->failOnError = $failOnError;
        $this->user = $user;
        parent::__construct();
    }

    /**
     * Get task name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get task description
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Check whether task should fail on error
     * @return bool
     */
    public function shouldFailOnError(): bool
    {
        return $this->failOnError;
    }
}
