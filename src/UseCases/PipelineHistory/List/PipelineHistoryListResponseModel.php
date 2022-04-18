<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineHistory\List;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class PipelineHistoryListResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelineHistory\List
 */
class PipelineHistoryListResponseModel
{
    /**
     * List of entities
     * @var Collection|EloquentCollection
     */
    private Collection|EloquentCollection $entities;

    /**
     * PipelineHistoryListResponseModel constructor.
     * @param EloquentCollection|Collection|array $entities
     * @return void
     */
    public function __construct(EloquentCollection|Collection|array $entities = [])
    {
        if (is_array($entities)) {
            $entities = collect($entities);
        }
        $this->entities = $entities;
    }

    /**
     * Get list of entities
     * @return EloquentCollection|Collection
     */
    public function getEntities(): EloquentCollection|Collection
    {
        return $this->entities;
    }
}
