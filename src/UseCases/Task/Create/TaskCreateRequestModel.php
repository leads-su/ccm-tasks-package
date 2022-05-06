<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Create;

use ConsulConfigManager\Tasks\Http\Requests\Task\TaskCreateUpdateRequest;

/**
 * Class TaskCreateRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\Task\Create
 */
class TaskCreateRequestModel
{
    /**
     * Request instance
     * @var TaskCreateUpdateRequest
     */
    private TaskCreateUpdateRequest $request;

    /**
     * TaskCreateRequestModel constructor.
     * @param TaskCreateUpdateRequest $request
     * @return void
     */
    public function __construct(TaskCreateUpdateRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Get request instance
     * @return TaskCreateUpdateRequest
     */
    public function getRequest(): TaskCreateUpdateRequest
    {
        return $this->request;
    }
}
