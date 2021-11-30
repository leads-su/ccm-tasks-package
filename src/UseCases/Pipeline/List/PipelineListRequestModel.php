<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\List;

use Illuminate\Http\Request;

/**
 * Class PipelineListRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\List
 */
class PipelineListRequestModel
{
    /**
     * Request instance
     * @var Request
     */
    private Request $request;

    /**
     * PipelineListRequestModel constructor.
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
