<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineHistory\Get;

use Illuminate\Http\Request;

/**
 * Class PipelineHistoryGetRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelineHistory\Get
 */
class PipelineHistoryGetRequestModel
{
    /**
     * Request instance
     * @var Request
     */
    private Request $request;

    /**
     * Entity identifier
     * @var string
     */
    private string $identifier;

    /**
     * PipelineHistoryListRequestModel constructor.
     * @param Request $request
     * @param string $identifier
     * @return void
     */
    public function __construct(Request $request, string $identifier)
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
     * Get entity identifier
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
