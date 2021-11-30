<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Create;

use ConsulConfigManager\Tasks\Http\Requests\Action\ActionCreateUpdateRequest;

/**
 * Class ActionCreateRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\Action\Create
 */
class ActionCreateRequestModel
{
    /**
     * Request instance
     * @var ActionCreateUpdateRequest
     */
    private ActionCreateUpdateRequest $request;

    /**
     * ActionCreateRequestModel constructor.
     * @param ActionCreateUpdateRequest $request
     * @return void
     */
    public function __construct(ActionCreateUpdateRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Get request instance
     * @return ActionCreateUpdateRequest
     */
    public function getRequest(): ActionCreateUpdateRequest
    {
        return $this->request;
    }
}
