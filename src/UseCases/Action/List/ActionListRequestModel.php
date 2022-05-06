<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\List;

use Illuminate\Http\Request;

/**
 * Class ActionListRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\Action\List
 */
class ActionListRequestModel
{
    /**
     * Request instance
     * @var Request
     */
    private Request $request;

    /**
     * ActionListRequestModel constructor.
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
