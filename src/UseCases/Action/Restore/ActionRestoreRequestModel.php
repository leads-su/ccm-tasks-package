<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Restore;

use Illuminate\Http\Request;

/**
 * Class ActionRestoreRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\Action\Restore
 */
class ActionRestoreRequestModel
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
     * ActionRestoreRequestModel constructor.
     * @param Request $request
     * @param string|int $identifier
     */
    public function __construct(Request $request, string|int $identifier)
    {
        $this->request = $request;
        $this->identifier = $identifier;
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
     * Restore identifier
     * @return string|int
     */
    public function getIdentifier(): string|int
    {
        return $this->identifier;
    }
}
