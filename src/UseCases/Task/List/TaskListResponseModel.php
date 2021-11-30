<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\List;

/**
 * Class TaskListResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\Task\List
 */
class TaskListResponseModel
{
    /**
     * List of entities
     * @var array
     */
    private array $entities;

    /**
     * TaskListResponseModel constructor.
     * @param array $entities
     * @return void
     */
    public function __construct(array $entities = [])
    {
        $this->entities = $entities;
    }

    /**
     * Get list of entities
     * @return array
     */
    public function getEntities(): array
    {
        return $this->entities;
    }
}
