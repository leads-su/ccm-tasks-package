<?php

namespace ConsulConfigManager\Tasks\Events\Task;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;

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
     * Task type
     * @var int
     */
    private int $type;

    /**
     * TaskUpdated constructor.
     * @param string $name
     * @param string $description
     * @param int $type
     * @param UserEntity|int|null $user
     * @return void
     */
    public function __construct(
        string $name,
        string $description,
        int $type,
        UserEntity|int|null $user = null,
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->type = $type;
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
     * Get task type
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }
}
