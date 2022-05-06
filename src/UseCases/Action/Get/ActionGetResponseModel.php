<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Get;

use ConsulConfigManager\Tasks\Interfaces\ActionInterface;

/**
 * Class ActionGetResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\Action\Get
 */
class ActionGetResponseModel
{
    /**
     * Get of entities
     * @var ActionInterface|array|null
     */
    private ActionInterface|array|null $entity;

    /**
     * ActionGetResponseModel constructor.
     * @param ActionInterface|array|null $entity
     */
    public function __construct(ActionInterface|array|null $entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * Get entity
     * @return array
     */
    public function getEntity(): array
    {
        if (is_null($this->entity)) {
            return [];
        } elseif (is_array($this->entity)) {
            return $this->entity;
        }
        return $this->entity->toArray();
    }
}
