<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineExecution\Get;

/**
 * Class PipelineExecutionGetResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelineExecution\Get
 */
class PipelineExecutionGetResponseModel
{
    /**
     * Entity array
     * @var array
     */
    private array $entity;

    /**
     * PipelineExecutionGetResponseModel constructor.
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
