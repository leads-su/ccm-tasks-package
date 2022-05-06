<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Get;

use ConsulConfigManager\Tasks\Interfaces\TaskActionInterface;

/**
 * Class TaskActionGetResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Get
 */
class TaskActionGetResponseModel
{
    /**
     * Entity instance
     * @var TaskActionInterface|null
     */
    private ?TaskActionInterface $entity;

    /**
     * TaskActionGetResponseModel constructor.
     * @param TaskActionInterface|null $entity
     * @return void
     */
    public function __construct(?TaskActionInterface $entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * Get entity instance
     * @return TaskActionInterface|null
     */
    public function getEntity(): ?TaskActionInterface
    {
        return $this->entity;
    }
}
