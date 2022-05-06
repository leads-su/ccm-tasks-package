<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\List;

/**
 * Class ActionListResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\Action\List
 */
class ActionListResponseModel
{
    /**
     * List of entities
     * @var array
     */
    private array $entities;

    /**
     * ActionListResponseModel constructor.
     * @param array $entities
     * @return void
     */
    public function __construct(array $entities = [])
    {
        $this->entities = $entities;
    }

    /**
     * Get list of entities
     * @return array
     */
    public function getEntities(): array
    {
        return $this->entities;
    }
}
