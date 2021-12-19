<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\List;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class TaskActionListResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\List
 */
class TaskActionListResponseModel
{
    /**
     * List of entities
     * @var Collection|EloquentCollection
     */
    private Collection|EloquentCollection $entities;

    /**
     * TaskActionListResponseModel constructor.
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
     * @return Collection|EloquentCollection
     */
    public function getEntities(): Collection|EloquentCollection
    {
        return $this->entities;
    }
}
