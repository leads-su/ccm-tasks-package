<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Update;

use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;

/**
 * Class PipelineTaskUpdateResponseModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Update
 */
class PipelineTaskUpdateResponseModel
{
    /**
     * Pipeline instance
     * @var PipelineInterface|null
     */
    private ?PipelineInterface $pipelineInstance;

    /**
     * Task instance
     * @var TaskInterface|null
     */
    private ?TaskInterface $taskInstance;

    /**
     * PipelineTaskUpdateResponseModel constructor.
     * @param PipelineInterface|null $pipelineInstance
     * @param TaskInterface|null $taskInstance
     * @return void
     */
    public function __construct(?PipelineInterface $pipelineInstance = null, ?TaskInterface $taskInstance = null)
    {
        $this->pipelineInstance = $pipelineInstance;
        $this->taskInstance = $taskInstance;
    }

    /**
     * Get pipeline instance
     * @return PipelineInterface|null
     */
    public function getPipelineInstance(): ?PipelineInterface
    {
        return $this->pipelineInstance;
    }

    /**
     * Get action instance
     * @return TaskInterface|null
     */
    public function getTaskInstance(): ?TaskInterface
    {
        return $this->taskInstance;
    }
}
