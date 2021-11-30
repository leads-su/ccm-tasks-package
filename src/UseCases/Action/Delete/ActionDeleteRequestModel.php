<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Delete;

use Illuminate\Http\Request;

/**
 * Class ActionDeleteRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\Action\Delete
 */
class ActionDeleteRequestModel
{
    /**
     * Request instance
     * @var Request
     */
    private Request $request;

    /**
     * Identifier string
     * @var string
     */
    private string $identifier;

    /**
     * ActionDeleteRequestModel constructor.
     * @param Request $request
     * @param string|int $identifier
     */
    public function __construct(Request $request, string|int $identifier)
    {
        $this->request = $request;
        $this->identifier = $identifier;
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
     * Delete identifier
     * @return string|int
     */
    public function getIdentifier(): string|int
    {
        return $this->identifier;
    }
}
