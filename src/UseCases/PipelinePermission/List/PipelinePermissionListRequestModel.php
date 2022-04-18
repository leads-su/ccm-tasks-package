<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\List;

use Illuminate\Http\Request;

/**
 * Class PipelinePermissionListRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\List
 */
class PipelinePermissionListRequestModel
{
    /**
     * Request instance
     * @var Request
     */
    private Request $request;

    /**
     * User identifier reference
     * @var string|int
     */
    private string|int $identifier;

    /**
     * PipelinePermissionListRequestModel constructor.
     * @param Request $request
     * @param string|int $identifier
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
     * Get user identifier reference
     * @return string|int
     */
    public function getIdentifier(): string|int
    {
        return $this->identifier;
    }
}
