<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Update;

use ConsulConfigManager\Tasks\Http\Requests\TaskAction\TaskActionCreateUpdateRequest;

/**
 * Class TaskActionUpdateRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Update
 */
class TaskActionUpdateRequestModel
{
    /**
     * Request instance
     * @var TaskActionCreateUpdateRequest
     */
    private TaskActionCreateUpdateRequest $request;

    /**
     * Task identifier
     * @var string
     */
    private string $task;

    /**
     * Action identifier
     * @var string
     */
    private string $action;

    /**
     * TaskActionUpdateRequestModel constructor.
     * @param TaskActionCreateUpdateRequest $request
     * @param string $taskIdentifier
     * @param string $actionIdentifier
     */
    public function __construct(TaskActionCreateUpdateRequest $request, string $taskIdentifier, string $actionIdentifier)
    {
        $this->request = $request;
        $this->task = $taskIdentifier;
        $this->action = $actionIdentifier;
    }

    /**
     * Get request instance
     * @return TaskActionCreateUpdateRequest
     */
    public function getRequest(): TaskActionCreateUpdateRequest
    {
        return $this->request;
    }

    /**
     * Get task identifier
     * @return string
     */
    public function getTask(): string
    {
        return $this->task;
    }

    /**
     * Get task action identifier
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }
}
