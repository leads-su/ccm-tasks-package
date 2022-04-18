<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineHistory\Get;

/**
 * Class PipelineHistoryGetResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelineHistory\Get
 */
class PipelineHistoryGetResponseModel
{
    /**
     * List of entities
     * @var array
     */
    private array $entity;

    /**
     * PipelineHistoryListResponseModel constructor.
     * @param array $entity
     */
    public function __construct(array $entity = [])
    {
        $this->entity = $entity;
    }

    /**
     * Get list of entities
     * @return array
     */
    public function getEntity(): array
    {
        return $this->entity;
    }
}
