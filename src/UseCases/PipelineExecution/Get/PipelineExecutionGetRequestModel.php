<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineExecution\Get;

use Illuminate\Http\Request;

/**
 * Class PipelineExecutionGetRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelineExecution\Get
 */
class PipelineExecutionGetRequestModel
{
    /**
     * Request instance
     * @var Request
     */
    private Request $request;

    /**
     * Entity identifier
     * @var string|int
     */
    private string|int $identifier;

    /**
     * Execution identifier
     * @var string|int
     */
    private string|int $execution;

    /**
     * PipelineExecutionGetRequestModel constructor.
     * @param Request $request
     * @param string|int $identifier
     * @param string|int $execution
     * @return void
     */
    public function __construct(Request $request, string|int $identifier, string|int $execution)
    {
        $this->request = $request;
        $this->identifier = $identifier;
        $this->execution = $execution;
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
     * Get entity identifier
     * @return string|int
     */
    public function getIdentifier(): string|int
    {
        return $this->identifier;
    }

    /**
     * Get execution identifier
     * @return string|int
     */
    public function getExecution(): string|int
    {
        return $this->execution;
    }
}
