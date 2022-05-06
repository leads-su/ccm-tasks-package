<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\Delete;

use ConsulConfigManager\Tasks\Http\Requests\PipelinePermission\PipelinePermissionCreateDeleteRequest;

/**
 * Class PipelinePermissionDeleteRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\Delete
 */
class PipelinePermissionDeleteRequestModel
{
    /**
     * Request instance
     * @var PipelinePermissionCreateDeleteRequest
     */
    private PipelinePermissionCreateDeleteRequest $request;

    /**
     * PipelinePermissionDeleteRequestModel constructor.
     * @param PipelinePermissionCreateDeleteRequest $request
     * @return void
     */
    public function __construct(PipelinePermissionCreateDeleteRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Get request instance
     * @return PipelinePermissionCreateDeleteRequest
     */
    public function getRequest(): PipelinePermissionCreateDeleteRequest
    {
        return $this->request;
    }

    /**
     * Get user identifier from request
     * @return int
     */
    public function getUserIdentifier(): int
    {
        return $this->getRequest()->get('user_id');
    }

    /**
     * Get pipeline identifier from request
     * @return string
     */
    public function getPipelineIdentifier(): string
    {
        return $this->getRequest()->get('pipeline_uuid');
    }
}
