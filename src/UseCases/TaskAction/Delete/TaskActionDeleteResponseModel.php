<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Delete;

use ConsulConfigManager\Tasks\Interfaces\ActionInterface;

/**
 * Class TaskActionDeleteResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Delete
 */
class TaskActionDeleteResponseModel
{
    /**
     * Entity instance
     * @var ActionInterface|null
     */
    private ?ActionInterface $entity;

    /**
     * TaskActionDeleteResponseModel constructor.
     * @param ActionInterface|null $entity
     * @return void
     */
    public function __construct(?ActionInterface $entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * Delete entity instance
     * @return ActionInterface|null
     */
    public function getEntity(): ?ActionInterface
    {
        return $this->entity;
    }
}
