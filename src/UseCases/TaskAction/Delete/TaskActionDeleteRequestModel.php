<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Delete;

use Illuminate\Http\Request;

/**
 * Class TaskActionDeleteRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Delete
 */
class TaskActionDeleteRequestModel
{
    /**
     * Request instance
     * @var Request
     */
    private Request $request;

    /**
     * Task identifier
     * @var string
     */
    private string $taskIdentifier;

    /**
     * Action identifier
     * @var string
     */
    private string $actionIdentifier;

    /**
     * TaskActionDeleteRequestModel constructor.
     * @param Request $request
     * @param string $taskIdentifier
     * @param string $actionIdentifier
     */
    public function __construct(Request $request, string $taskIdentifier, string $actionIdentifier)
    {
        $this->request = $request;
        $this->taskIdentifier = $taskIdentifier;
        $this->actionIdentifier = $actionIdentifier;
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
     * Get task identifier
     * @return string
     */
    public function getTaskIdentifier(): string
    {
        return $this->taskIdentifier;
    }

    /**
     * Get action identifier
     * @return string
     */
    public function getActionIdentifier(): string
    {
        return $this->actionIdentifier;
    }
}
