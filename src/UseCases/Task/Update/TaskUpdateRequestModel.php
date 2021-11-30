<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Update;

use ConsulConfigManager\Tasks\Http\Requests\Task\TaskCreateUpdateRequest;

/**
 * Class TaskUpdateRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\Task\Update
 */
class TaskUpdateRequestModel
{
    /**
     * Request instance
     * @var TaskCreateUpdateRequest
     */
    private TaskCreateUpdateRequest $request;

    /**
     * Entity identifier
     * @var string|int
     */
    private string|int $identifier;

    /**
     * TaskUpdateRequestModel constructor.
     * @param TaskCreateUpdateRequest $request
     * @param string|int $identifier
     */
    public function __construct(TaskCreateUpdateRequest $request, string|int $identifier)
    {
        $this->request = $request;
        $this->identifier = $identifier;
    }

    /**
     * Get request instance
     * @return TaskCreateUpdateRequest
     */
    public function getRequest(): TaskCreateUpdateRequest
    {
        return $this->request;
    }

    /**
     * Get entity identifier
     * @return string|int
     */
    public function getIdentifier(): string|int
    {
        return $this->identifier;
    }
}
