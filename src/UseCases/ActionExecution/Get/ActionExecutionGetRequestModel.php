<?php

namespace ConsulConfigManager\Tasks\UseCases\ActionExecution\Get;

use Illuminate\Http\Request;

/**
 * Class ActionExecutionGetRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\ActionExecution\Get
 */
class ActionExecutionGetRequestModel {

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
     * Execution identifier
     * @var int
     */
    private int $execution;

    /**
     * ActionExecutionGetRequestModel constructor.
     * @param Request $request
     * @param string $identifier
     * @param int $execution
     * @return void
     */
    public function __construct(Request $request, string $identifier, int $execution) {
        $this->request = $request;
        $this->identifier = $identifier;
        $this->execution = $execution;
    }

    /**
     * Get request instance
     * @return Request
     */
    public function getRequest(): Request {
        return $this->request;
    }

    /**
     * Get entity identifier
     * @return string
     */
    public function getIdentifier(): string {
        return $this->identifier;
    }

    /**
     * Get execution identifier
     * @return int
     */
    public function getExecution(): int {
        return $this->execution;
    }

}
