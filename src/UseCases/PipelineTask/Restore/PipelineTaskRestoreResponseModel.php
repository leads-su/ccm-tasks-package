<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Restore;

use ConsulConfigManager\Tasks\Interfaces\TaskInterface;

/**
 * Class PipelineTaskRestoreResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Restore
 */
class PipelineTaskRestoreResponseModel
{
    /**
     * Entity instance
     * @var TaskInterface|null
     */
    private ?TaskInterface $entity;

    /**
     * PipelineTaskRestoreResponseModel constructor.
     * @param TaskInterface|null $entity
     * @return void
     */
    public function __construct(?TaskInterface $entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * Restore entity instance
     * @return TaskInterface|null
     */
    public function getEntity(): ?TaskInterface
    {
        return $this->entity;
    }
}
