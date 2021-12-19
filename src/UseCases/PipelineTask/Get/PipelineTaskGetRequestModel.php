<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Get;

use Illuminate\Http\Request;

/**
 * Class PipelineTaskGetRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Get
 */
class PipelineTaskGetRequestModel
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
     * PipelineTaskGetRequestModel constructor.
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
     * Get request instance
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
