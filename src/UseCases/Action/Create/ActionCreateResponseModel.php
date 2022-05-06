<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Create;

use ConsulConfigManager\Tasks\Interfaces\ActionInterface;

/**
 * Class ActionCreateResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\Action\Create
 */
class ActionCreateResponseModel
{
    /**
     * Entity instance
     * @var ActionInterface|null
     */
    private ?ActionInterface $entity;

    /**
     * ActionCreateResponseModel constructor.
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
