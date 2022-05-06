<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineHistory\List;

use Illuminate\Http\Request;

/**
 * Class PipelineHistoryListRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelineHistory\List
 */
class PipelineHistoryListRequestModel
{
    /**
     * Request instance
     * @var Request
     */
    private Request $request;

    /**
     * PipelineHistoryListRequestModel constructor.
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
