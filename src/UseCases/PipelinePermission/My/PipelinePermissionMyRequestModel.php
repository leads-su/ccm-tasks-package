<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\My;

use Illuminate\Http\Request;

/**
 * Class PipelinePermissionMyRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\My
 */
class PipelinePermissionMyRequestModel
{
    /**
     * Request instance
     * @var Request
     */
    private Request $request;

    /**
     * PipelinePermissionMyRequestModel constructor.
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
