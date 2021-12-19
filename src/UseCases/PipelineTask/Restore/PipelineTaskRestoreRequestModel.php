<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Restore;

use Illuminate\Http\Request;

/**
 * Class PipelineTaskRestoreRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Restore
 */
class PipelineTaskRestoreRequestModel
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
     * PipelineTaskRestoreRequestModel constructor.
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
     * Restore request instance
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
