<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Create;

use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskActionInterface;

/**
 * Class TaskActionCreateResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Create
 */
class TaskActionCreateResponseModel
{
    /**
     * Entity instance
     * @var TaskActionInterface|null
     */
    private ?TaskActionInterface $entity;

    /**
     * TaskActionCreateResponseModel constructor.
     * @param TaskActionInterface|null $entity
     */
    public function __construct(?TaskActionInterface $entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * Get entity
     * @return TaskInterface|null
     */
    public function getEntity(): ?TaskActionInterface
    {
        return $this->entity;
    }
}
