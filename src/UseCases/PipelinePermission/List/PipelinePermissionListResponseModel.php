<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\List;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class PipelinePermissionListResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\List
 */
class PipelinePermissionListResponseModel
{
    /**
     * Entities collection
     * @var Collection|EloquentCollection
     */
    private Collection|EloquentCollection $entities;

    /**
     * PipelinePermissionListResponseModel constructor.
     * @param Collection|EloquentCollection|array $entities
     * @return void
     */
    public function __construct(Collection|EloquentCollection|array $entities = [])
    {
        if (is_array($entities)) {
            $entities = collect($entities);
        }
        $this->entities = $entities;
    }

    /**
     * Get list of entities
     * @return array
     */
    public function getEntities(): array
    {
        return $this->entities->toArray();
    }
}
