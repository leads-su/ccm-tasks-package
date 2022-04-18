<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\Create;

use ConsulConfigManager\Tasks\Interfaces\PipelinePermissionInterface;

/**
 * Class PipelinePermissionCreateResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\Create
 */
class PipelinePermissionCreateResponseModel
{
    /**
     * Entity instance
     * @var PipelinePermissionInterface|null
     */
    private ?PipelinePermissionInterface $entity;

    /**
     * PipelinePermissionCreateResponseModel constructor.
     * @param PipelinePermissionInterface|null $entity
     * @return void
     */
    public function __construct(?PipelinePermissionInterface $entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * Get entity
     * @return array
     */
    public function getEntity(): array
    {
        if ($this->entity) {
            return $this->entity->toArray();
        }
        return [];
    }
}
