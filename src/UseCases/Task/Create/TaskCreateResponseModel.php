<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Create;

use ConsulConfigManager\Tasks\Interfaces\TaskInterface;

/**
 * Class TaskCreateResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\Task\Create
 */
class TaskCreateResponseModel
{
    /**
     * Entity instance
     * @var TaskInterface|null
     */
    private ?TaskInterface $entity;

    /**
     * TaskCreateResponseModel constructor.
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
