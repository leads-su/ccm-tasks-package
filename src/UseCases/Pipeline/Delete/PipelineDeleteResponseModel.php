<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Delete;

use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;

/**
 * Class PipelineDeleteResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Delete
 */
class PipelineDeleteResponseModel
{
    /**
     * Delete of entities
     * @var PipelineInterface|null
     */
    private ?PipelineInterface $entity;

    /**
     * PipelineDeleteResponseModel constructor.
     * @param PipelineInterface|null $entity
     */
    public function __construct(?PipelineInterface $entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * Get entity
     * @return PipelineInterface|null
     */
    public function getEntity(): ?PipelineInterface
    {
        return $this->entity;
    }
}
