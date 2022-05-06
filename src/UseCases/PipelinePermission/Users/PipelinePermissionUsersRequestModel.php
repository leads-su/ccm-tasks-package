<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\Users;

use Illuminate\Http\Request;

/**
 * Class PipelinePermissionUsersRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\Users
 */
class PipelinePermissionUsersRequestModel
{
    /**
     * Request instance
     * @var Request
     */
    private Request $request;

    /**
     * PipelinePermissionUsersRequestModel constructor.
     * @param Request $request
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
