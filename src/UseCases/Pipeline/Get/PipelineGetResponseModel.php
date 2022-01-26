<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Get;

use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;

/**
 * Class PipelineGetResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Get
 */
class PipelineGetResponseModel
{
    /**
     * Get of entities
     * @var PipelineInterface|array|null
     */
    private PipelineInterface|array|null $entity;

    /**
     * PipelineGetResponseModel constructor.
     * @param PipelineInterface|array|null $entity
     */
    public function __construct(PipelineInterface|array|null $entity = null)
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
