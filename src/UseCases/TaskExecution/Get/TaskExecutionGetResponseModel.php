<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskExecution\Get;

/**
 * Class TaskExecutionGetResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\TaskExecution\Get
 */
class TaskExecutionGetResponseModel
{
    /**
     * Entity array
     * @var array
     */
    private array $entity;

    /**
     * TaskExecutionGetResponseModel constructor.
     * @param array $entity
     * @return void
     */
    public function __construct(array $entity = [])
    {
        $this->entity = $entity;
    }

    /**
     * Get entity array
     * @return array
     */
    public function getEntity(): array
    {
        return $this->entity;
    }
}
