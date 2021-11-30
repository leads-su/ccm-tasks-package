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
     * @var ActionInterface|null
     */
    private ?ActionInterface $entity;

    /**
     * ActionGetResponseModel constructor.
     * @param ActionInterface|null $entity
     */
    public function __construct(?ActionInterface $entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * Get entity
     * @return ActionInterface|null
     */
    public function getEntity(): ?ActionInterface
    {
        return $this->entity;
    }
}
