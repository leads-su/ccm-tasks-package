<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\My;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class PipelinePermissionMyResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\My
 */
class PipelinePermissionMyResponseModel
{
    /**
     * Entities collection
     * @var Collection|EloquentCollection
     */
    private Collection|EloquentCollection $entities;

    /**
     * PipelinePermissionMyResponseModel constructor.
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
