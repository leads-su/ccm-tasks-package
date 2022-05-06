<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Restore;

use ConsulConfigManager\Tasks\Interfaces\ActionInterface;

/**
 * Class ActionRestoreResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\Action\Restore
 */
class ActionRestoreResponseModel
{
    /**
     * Restore of entities
     * @var ActionInterface|null
     */
    private ?ActionInterface $entity;

    /**
     * ActionRestoreResponseModel constructor.
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
