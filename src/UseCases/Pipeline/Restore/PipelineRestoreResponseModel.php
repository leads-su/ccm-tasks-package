<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Restore;

use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;

/**
 * Class PipelineRestoreResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Restore
 */
class PipelineRestoreResponseModel
{
    /**
     * Restore of entities
     * @var PipelineInterface|null
     */
    private ?PipelineInterface $entity;

    /**
     * PipelineRestoreResponseModel constructor.
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
