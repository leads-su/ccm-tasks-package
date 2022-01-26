<?php

namespace ConsulConfigManager\Tasks\UseCases\Service\List;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class ServiceListResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\Service\List
 */
class ServiceListResponseModel
{
    /**
     * List of entities
     * @var Collection|EloquentCollection
     */
    private Collection|EloquentCollection $entities;

    /**
     * ServiceListResponseModel constructor.
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
     * @return Collection|EloquentCollection
     */
    public function getEntities(): Collection|EloquentCollection
    {
        return $this->entities;
    }
}
