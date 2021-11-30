<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Update;

use ConsulConfigManager\Tasks\Interfaces\ActionInterface;

/**
 * Class ActionUpdateResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\Action\Update
 */
class ActionUpdateResponseModel
{
    /**
     * Entity instance
     * @var ActionInterface|null
     */
    private ?ActionInterface $entity;

    /**
     * ActionUpdateResponseModel constructor.
     * @param ActionInterface|null $entity
     * @return void
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
