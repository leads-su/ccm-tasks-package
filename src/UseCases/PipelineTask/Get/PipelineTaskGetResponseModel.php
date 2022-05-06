<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Get;

use ConsulConfigManager\Tasks\Interfaces\PipelineTaskInterface;

/**
 * Class PipelineTaskGetResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Get
 */
class PipelineTaskGetResponseModel
{
    /**
     * Entity instance
     * @var PipelineTaskInterface|null
     */
    private ?PipelineTaskInterface $entity;

    /**
     * PipelineTaskGetResponseModel constructor.
     * @param PipelineTaskInterface|null $entity
     * @return void
     */
    public function __construct(?PipelineTaskInterface $entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * Get entity instance
     * @return PipelineTaskInterface|null
     */
    public function getEntity(): ?PipelineTaskInterface
    {
        return $this->entity;
    }
}
