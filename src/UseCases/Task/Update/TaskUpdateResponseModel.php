<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Update;

use ConsulConfigManager\Tasks\Interfaces\TaskInterface;

/**
 * Class TaskUpdateResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\Task\Update
 */
class TaskUpdateResponseModel
{
    /**
     * Entity instance
     * @var TaskInterface|null
     */
    private ?TaskInterface $entity;

    /**
     * TaskUpdateResponseModel constructor.
     * @param TaskInterface|null $entity
     * @return void
     */
    public function __construct(?TaskInterface $entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * Get entity
     * @return TaskInterface|null
     */
    public function getEntity(): ?TaskInterface
    {
        return $this->entity;
    }
}
