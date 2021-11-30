<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Update;

use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;

/**
 * Class PipelineUpdateResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Update
 */
class PipelineUpdateResponseModel
{
    /**
     * Entity instance
     * @var PipelineInterface|null
     */
    private ?PipelineInterface $entity;

    /**
     * PipelineUpdateResponseModel constructor.
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
