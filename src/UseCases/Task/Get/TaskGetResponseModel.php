<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Get;

use ConsulConfigManager\Tasks\Interfaces\TaskInterface;

/**
 * Class TaskGetResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\Task\Get
 */
class TaskGetResponseModel
{
    /**
     * Get of entities
     * @var TaskInterface|null
     */
    private ?TaskInterface $entity;

    /**
     * TaskGetResponseModel constructor.
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
