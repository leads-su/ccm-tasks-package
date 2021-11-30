<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\List;

use Illuminate\Http\Request;

/**
 * Class TaskListRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\Task\List
 */
class TaskListRequestModel
{
    /**
     * Request instance
     * @var Request
     */
    private Request $request;

    /**
     * TaskListRequestModel constructor.
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get request instance
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
