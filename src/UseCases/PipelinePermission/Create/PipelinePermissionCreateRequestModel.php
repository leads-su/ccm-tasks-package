<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\Create;

use ConsulConfigManager\Tasks\Http\Requests\PipelinePermission\PipelinePermissionCreateDeleteRequest;

/**
 * Class PipelinePermissionCreateRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\Create
 */
class PipelinePermissionCreateRequestModel
{
    /**
     * Request instance
     * @var PipelinePermissionCreateDeleteRequest
     */
    private PipelinePermissionCreateDeleteRequest $request;

    /**
     * PipelinePermissionCreateRequestModel constructor.
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
