<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\List;

use Illuminate\Http\Request;

/**
 * Class TaskActionListRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\List
 */
class TaskActionListRequestModel
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
     * TaskActionListRequestModel constructor.
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
