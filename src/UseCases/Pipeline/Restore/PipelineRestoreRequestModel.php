<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Restore;

use Illuminate\Http\Request;

/**
 * Class PipelineRestoreRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Restore
 */
class PipelineRestoreRequestModel
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
     * PipelineRestoreRequestModel constructor.
     * @param Request $request
     * @param string|int $identifier
     */
    public function __construct(Request $request, string|int $identifier)
    {
        $this->request = $request;
        $this->identifier = $identifier;
    }

    /**
     * Restore request instance
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Restore identifier
     * @return string|int
     */
    public function getIdentifier(): string|int
    {
        return $this->identifier;
    }
}
