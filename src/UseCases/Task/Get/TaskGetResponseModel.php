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
    private TaskInterface|array|null $entity;

    /**
     * TaskGetResponseModel constructor.
     * @param TaskInterface|array|null $entity
     */
    public function __construct(TaskInterface|array|null $entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * Get entity
     * @return array
     */
    public function getEntity(): array
    {
        if ($this->entity === null) {
            return [];
        }
        if (is_array($this->entity)) {
            return $this->entity;
        }
        return $this->entity->toArray();
    }
}
