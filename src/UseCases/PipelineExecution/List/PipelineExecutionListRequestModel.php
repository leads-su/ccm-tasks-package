<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineExecution\List;

use Illuminate\Http\Request;

/**
 * Class PipelineExecutionListRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelineExecution\List
 */
class PipelineExecutionListRequestModel
{
    /**
     * Request instance
     * @var Request
     */
    private Request $request;

    /**
     * PipelineExecutionListRequestModel constructor.
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get request instance
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
