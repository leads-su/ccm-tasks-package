<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Update;

use ConsulConfigManager\Tasks\Http\Requests\Pipeline\PipelineCreateUpdateRequest;

/**
 * Class PipelineUpdateRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Update
 */
class PipelineUpdateRequestModel
{
    /**
     * Request instance
     * @var PipelineCreateUpdateRequest
     */
    private PipelineCreateUpdateRequest $request;

    /**
     * Entity identifier
     * @var string|int
     */
    private string|int $identifier;

    /**
     * PipelineUpdateRequestModel constructor.
     * @param PipelineCreateUpdateRequest $request
     * @param string|int $identifier
     */
    public function __construct(PipelineCreateUpdateRequest $request, string|int $identifier)
    {
        $this->request = $request;
        $this->identifier = $identifier;
    }

    /**
     * Get request instance
     * @return PipelineCreateUpdateRequest
     */
    public function getRequest(): PipelineCreateUpdateRequest
    {
        return $this->request;
    }

    /**
     * Get entity identifier
     * @return string|int
     */
    public function getIdentifier(): string|int
    {
        return $this->identifier;
    }
}
