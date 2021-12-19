<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\List;

use Illuminate\Http\Request;

/**
 * Class PipelineTaskListRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\List
 */
class PipelineTaskListRequestModel
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
     * PipelineTaskListRequestModel constructor.
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
