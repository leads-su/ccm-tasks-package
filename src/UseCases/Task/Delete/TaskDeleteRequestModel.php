<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Delete;

use Illuminate\Http\Request;

/**
 * Class TaskDeleteRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\Task\Delete
 */
class TaskDeleteRequestModel
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
     * TaskDeleteRequestModel constructor.
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
