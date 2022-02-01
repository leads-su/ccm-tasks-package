<?php

namespace ConsulConfigManager\Tasks\UseCases\ActionExecution\List;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;


/**
 * Class ActionExecutionListResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\ActionExecution\List
 */
class ActionExecutionListResponseModel {

    /**
     * List of entities
     * @var Collection|EloquentCollection
     */
    private Collection|EloquentCollection $entities;

    /**
     * ActionExecutionListResponseModel constructor.
     * @param EloquentCollection|Collection|array $entities
     * @return void
     */
    public function __construct(EloquentCollection|Collection|array $entities = []) {
        if (is_array($entities)) {
            $entities = collect($entities);
        }
        $this->entities = $entities;
    }

    /**
     * Get list of entities
     * @return EloquentCollection|Collection
     */
    public function getEntities(): EloquentCollection|Collection {
        return $this->entities;
    }

}
