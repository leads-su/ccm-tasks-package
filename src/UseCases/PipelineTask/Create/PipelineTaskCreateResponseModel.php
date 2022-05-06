<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Create;

use ConsulConfigManager\Tasks\Interfaces\PipelineTaskInterface;

/**
 * Class PipelineTaskCreateResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Create
 */
class PipelineTaskCreateResponseModel
{
    /**
     * Entity instance
     * @var PipelineTaskInterface|null
     */
    private ?PipelineTaskInterface $entity;

    /**
     * PipelineTaskCreateResponseModel constructor.
     * @param PipelineTaskInterface|null $entity
     */
    public function __construct(?PipelineTaskInterface $entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * Get entity
     * @return PipelineTaskInterface|null
     */
    public function getEntity(): ?PipelineTaskInterface
    {
        return $this->entity;
    }
}
