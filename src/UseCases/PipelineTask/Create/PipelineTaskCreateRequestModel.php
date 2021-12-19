<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Create;

use ConsulConfigManager\Tasks\Http\Requests\PipelineTask\PipelineTaskCreateUpdateRequest;

/**
 * Class PipelineTaskCreateRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Create
 */
class PipelineTaskCreateRequestModel
{
    /**
     * Request instance
     * @var PipelineTaskCreateUpdateRequest
     */
    private PipelineTaskCreateUpdateRequest $request;

    /**
     * Pipeline identifier
     * @var string
     */
    private string $pipelineIdentifier;

    /**
     * PipelineTaskCreateRequestModel constructor.
     * @param PipelineTaskCreateUpdateRequest $request
     * @param string $pipelineIdentifier
     * @return void
     */
    public function __construct(PipelineTaskCreateUpdateRequest $request, string $pipelineIdentifier)
    {
        $this->request = $request;
        $this->pipelineIdentifier = $pipelineIdentifier;
    }

    /**
     * Get request instance
     * @return PipelineTaskCreateUpdateRequest
     */
    public function getRequest(): PipelineTaskCreateUpdateRequest
    {
        return $this->request;
    }

    /**
     * Get pipeline identifier
     * @return string
     */
    public function getPipelineIdentifier(): string
    {
        return $this->pipelineIdentifier;
    }
}
