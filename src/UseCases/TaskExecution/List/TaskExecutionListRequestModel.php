<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskExecution\List;

use Illuminate\Http\Request;

/**
 * Class TaskExecutionListRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\TaskExecution\List
 */
class TaskExecutionListRequestModel
{
    /**
     * Request instance
     * @var Request
     */
    private Request $request;

    /**
     * Entity identifier
     * @var string
     */
    private string $identifier;

    /**
     * ActionExecutionListRequestModel constructor.
     * @param Request $request
     * @param string $identifier
     * @return void
     */
    public function __construct(Request $request, string $identifier)
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
     * Get entity identifier
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
