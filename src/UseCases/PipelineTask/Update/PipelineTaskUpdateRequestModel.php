<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Update;

use ConsulConfigManager\Tasks\Http\Requests\PipelineTask\PipelineTaskCreateUpdateRequest;

/**
 * Class PipelineTaskUpdateRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Update
 */
class PipelineTaskUpdateRequestModel
{
    /**
     * Request instance
     * @var PipelineTaskCreateUpdateRequest
     */
    private PipelineTaskCreateUpdateRequest $request;

    /**
     * Pipeline identifier
     * @var string
     */
    private string $pipelineIdentifier;

    /**
     * Task identifier
     * @var string
     */
    private string $taskIdentifier;

    /**
     * PipelineTaskUpdateRequestModel constructor.
     * @param PipelineTaskCreateUpdateRequest $request
     * @param string $pipelineIdentifier
     * @param string $taskIdentifier
     */
    public function __construct(PipelineTaskCreateUpdateRequest $request, string $pipelineIdentifier, string $taskIdentifier)
    {
        $this->request = $request;
        $this->pipelineIdentifier = $pipelineIdentifier;
        $this->taskIdentifier = $taskIdentifier;
    }

    /**
     * Get request instance
     * @return PipelineTaskCreateUpdateRequest
     */
    public function getRequest(): PipelineTaskCreateUpdateRequest
    {
        return $this->request;
    }

    /**
     * Get pipeline identifier
     * @return string
     */
    public function getPipelineIdentifier(): string
    {
        return $this->pipelineIdentifier;
    }

    /**
     * Get action identifier
     * @return string
     */
    public function getTaskIdentifier(): string
    {
        return $this->taskIdentifier;
    }
}
