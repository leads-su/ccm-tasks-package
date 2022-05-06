<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\List;

/**
 * Class PipelineListResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\List
 */
class PipelineListResponseModel
{
    /**
     * List of entities
     * @var array
     */
    private array $entities;

    /**
     * PipelineListResponseModel constructor.
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
