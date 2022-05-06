<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Delete;

use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;

/**
 * Class PipelineTaskDeleteResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Delete
 */
class PipelineTaskDeleteResponseModel
{
    /**
     * Entity instance
     * @var PipelineInterface|null
     */
    private ?PipelineInterface $entity;

    /**
     * PipelineTaskDeleteResponseModel constructor.
     * @param PipelineInterface|null $entity
     * @return void
     */
    public function __construct(?PipelineInterface $entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * Delete entity instance
     * @return PipelineInterface|null
     */
    public function getEntity(): ?PipelineInterface
    {
        return $this->entity;
    }
}
