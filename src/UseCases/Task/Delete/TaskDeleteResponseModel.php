<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Delete;

use ConsulConfigManager\Tasks\Interfaces\TaskInterface;

/**
 * Class TaskDeleteResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\Task\Delete
 */
class TaskDeleteResponseModel
{
    /**
     * Delete of entities
     * @var TaskInterface|null
     */
    private ?TaskInterface $entity;

    /**
     * TaskDeleteResponseModel constructor.
     * @param TaskInterface|null $entity
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
