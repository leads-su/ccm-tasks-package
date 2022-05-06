<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Delete;

use Illuminate\Http\Request;

/**
 * Class PipelineDeleteRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Delete
 */
class PipelineDeleteRequestModel
{
    /**
     * Request instance
     * @var Request
     */
    private Request $request;

    /**
     * Identifier string
     * @var string
     */
    private string $identifier;

    /**
     * PipelineDeleteRequestModel constructor.
     * @param Request $request
     * @param string|int $identifier
     */
    public function __construct(Request $request, string|int $identifier)
    {
        $this->request = $request;
        $this->identifier = $identifier;
    }

    /**
     * Delete request instance
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Delete identifier
     * @return string|int
     */
    public function getIdentifier(): string|int
    {
        return $this->identifier;
    }
}
