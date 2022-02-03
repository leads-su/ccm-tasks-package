<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineExecution\Get;


/**
 * Class PipelineExecutionGetResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelineExecution\Get
 */
class PipelineExecutionGetResponseModel {

    /**
     * List of entities
     * @var array
     */
    private array $entity;

    /**
     * PipelineExecutionListResponseModel constructor.
     * @param array $entity
     */
    public function __construct(array $entity = []) {
        $this->entity = $entity;
    }

    /**
     * Get list of entities
     * @return array
     */
    public function getEntity(): array {
        return $this->entity;
    }

}
