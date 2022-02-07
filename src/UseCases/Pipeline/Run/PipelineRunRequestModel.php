<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Run;

use Illuminate\Http\Request;

/**
 * Class PipelineRunRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Run
 */
class PipelineRunRequestModel
{
    /**
     * Request instance
     * @var Request
     */
    private Request $request;

    /**
     * Identifier string
     * @var string|int
     */
    private string|int $identifier;

    /**
     * PipelineRunRequestModel constructor.
     * @param Request $request
     * @param string|int $identifier
     * @return void
     */
    public function __construct(Request $request, string|int $identifier)
    {
        $this->request = $request;
        $this->identifier = $identifier;
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
     * Get identifier
     * @return string|int
     */
    public function getIdentifier(): string|int
    {
        return $this->identifier;
    }
}
