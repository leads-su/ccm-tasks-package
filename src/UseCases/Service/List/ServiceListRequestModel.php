<?php

namespace ConsulConfigManager\Tasks\UseCases\Service\List;

use Illuminate\Http\Request;

/**
 * Class ServiceListRequestModel
 * @package ConsulConfigManager\Tasks\UseCases\Service\List
 */
class ServiceListRequestModel
{
    /**
     * Request instance
     * @var Request
     */
    private Request $request;

    /**
     * ServiceListRequestModel constructor.
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
