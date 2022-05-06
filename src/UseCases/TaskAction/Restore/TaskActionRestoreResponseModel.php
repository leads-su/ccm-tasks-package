<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Restore;

use ConsulConfigManager\Tasks\Interfaces\ActionInterface;

/**
 * Class TaskActionRestoreResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Restore
 */
class TaskActionRestoreResponseModel
{
    /**
     * Entity instance
     * @var ActionInterface|null
     */
    private ?ActionInterface $entity;

    /**
     * TaskActionRestoreResponseModel constructor.
     * @param ActionInterface|null $entity
     * @return void
     */
    public function __construct(?ActionInterface $entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * Restore entity instance
     * @return ActionInterface|null
     */
    public function getEntity(): ?ActionInterface
    {
        return $this->entity;
    }
}
