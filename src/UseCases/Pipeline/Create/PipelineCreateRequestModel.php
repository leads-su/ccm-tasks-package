<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Create;

use ConsulConfigManager\Tasks\Http\Requests\Pipeline\PipelineCreateUpdateRequest;

/**
 * Class PipelineCreateRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Create
 */
class PipelineCreateRequestModel
{
    /**
     * Request instance
     * @var PipelineCreateUpdateRequest
     */
    private PipelineCreateUpdateRequest $request;

    /**
     * PipelineCreateRequestModel constructor.
     * @param PipelineCreateUpdateRequest $request
     * @return void
     */
    public function __construct(PipelineCreateUpdateRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Get request instance
     * @return PipelineCreateUpdateRequest
     */
    public function getRequest(): PipelineCreateUpdateRequest
    {
        return $this->request;
    }
}
