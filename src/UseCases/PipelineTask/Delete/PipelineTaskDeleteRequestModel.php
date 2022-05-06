<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Delete;

use Illuminate\Http\Request;

/**
 * Class PipelineTaskDeleteRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Delete
 */
class PipelineTaskDeleteRequestModel
{
    /**
     * Request instance
     * @var Request
     */
    private Request $request;

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
     * PipelineTaskDeleteRequestModel constructor.
     * @param Request $request
     * @param string $pipelineIdentifier
     * @param string $taskIdentifier
     */
    public function __construct(Request $request, string $pipelineIdentifier, string $taskIdentifier)
    {
        $this->request = $request;
        $this->pipelineIdentifier = $pipelineIdentifier;
        $this->taskIdentifier = $taskIdentifier;
    }

    /**
     * Delete request instance
     * @return Request
     */
    public function getRequest(): Request
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
