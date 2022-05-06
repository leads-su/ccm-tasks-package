<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Update;

use ConsulConfigManager\Tasks\Http\Requests\Action\ActionCreateUpdateRequest;

/**
 * Class ActionUpdateRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\Action\Update
 */
class ActionUpdateRequestModel
{
    /**
     * Request instance
     * @var ActionCreateUpdateRequest
     */
    private ActionCreateUpdateRequest $request;

    /**
     * Entity identifier
     * @var string|int
     */
    private string|int $identifier;

    /**
     * ActionUpdateRequestModel constructor.
     * @param ActionCreateUpdateRequest $request
     * @param string|int $identifier
     */
    public function __construct(ActionCreateUpdateRequest $request, string|int $identifier)
    {
        $this->request = $request;
        $this->identifier = $identifier;
    }

    /**
     * Get request instance
     * @return ActionCreateUpdateRequest
     */
    public function getRequest(): ActionCreateUpdateRequest
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
