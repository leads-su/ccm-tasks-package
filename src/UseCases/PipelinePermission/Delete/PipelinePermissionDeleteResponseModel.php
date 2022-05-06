<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\Delete;

use ConsulConfigManager\Tasks\Interfaces\PipelinePermissionInterface;

/**
 * Class PipelinePermissionDeleteResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\Delete
 */
class PipelinePermissionDeleteResponseModel
{
    /**
     * Entity instance
     * @var PipelinePermissionInterface|null
     */
    private ?PipelinePermissionInterface $entity;

    /**
     * PipelinePermissionDeleteResponseModel constructor.
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
