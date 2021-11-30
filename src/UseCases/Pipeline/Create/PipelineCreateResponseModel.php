<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Create;

use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;

/**
 * Class PipelineCreateResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Create
 */
class PipelineCreateResponseModel
{
    /**
     * Entity instance
     * @var PipelineInterface|null
     */
    private ?PipelineInterface $entity;

    /**
     * PipelineCreateResponseModel constructor.
     * @param PipelineInterface|null $entity
     * @return void
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
