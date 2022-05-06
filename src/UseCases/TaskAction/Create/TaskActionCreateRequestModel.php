<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Create;

use ConsulConfigManager\Tasks\Http\Requests\TaskAction\TaskActionCreateUpdateRequest;

/**
 * Class TaskActionCreateRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Create
 */
class TaskActionCreateRequestModel
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
     * TaskActionCreateRequestModel constructor.
     * @param TaskActionCreateUpdateRequest $request
     * @param string $identifier
     * @return void
     */
    public function __construct(TaskActionCreateUpdateRequest $request, string $identifier)
    {
        $this->request = $request;
        $this->task = $identifier;
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
}
