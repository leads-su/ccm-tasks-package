<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Restore;

use ConsulConfigManager\Tasks\Interfaces\TaskInterface;

/**
 * Class TaskRestoreResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\Task\Restore
 */
class TaskRestoreResponseModel
{
    /**
     * Restore of entities
     * @var TaskInterface|null
     */
    private ?TaskInterface $entity;

    /**
     * TaskRestoreResponseModel constructor.
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
