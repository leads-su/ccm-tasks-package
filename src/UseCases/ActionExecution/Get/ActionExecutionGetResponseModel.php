<?php

namespace ConsulConfigManager\Tasks\UseCases\ActionExecution\Get;

/**
 * Class ActionExecutionGetResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\ActionExecution\Get
 */
class ActionExecutionGetResponseModel
{
    /**
     * Entity array
     * @var array
     */
    private array $entity;

    /**
     * ActionExecutionGetResponseModel constructor.
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
