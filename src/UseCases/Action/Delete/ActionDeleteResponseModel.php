<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Delete;

use ConsulConfigManager\Tasks\Interfaces\ActionInterface;

/**
 * Class ActionDeleteResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\Action\Delete
 */
class ActionDeleteResponseModel
{
    /**
     * Delete of entities
     * @var ActionInterface|null
     */
    private ?ActionInterface $entity;

    /**
     * ActionDeleteResponseModel constructor.
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
