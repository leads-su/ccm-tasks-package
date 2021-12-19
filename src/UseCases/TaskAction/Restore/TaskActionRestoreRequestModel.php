<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Restore;

use Illuminate\Http\Request;

/**
 * Class TaskActionRestoreRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Restore
 */
class TaskActionRestoreRequestModel
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
     * TaskActionRestoreRequestModel constructor.
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
     * Restore request instance
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
