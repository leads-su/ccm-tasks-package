<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Get;

use Illuminate\Http\Request;

/**
 * Class TaskGetRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\Task\Get
 */
class TaskGetRequestModel
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
     * TaskGetRequestModel constructor.
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
     * Get identifier
     * @return string|int
     */
    public function getIdentifier(): string|int
    {
        return $this->identifier;
    }
}
