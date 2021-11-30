<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Get;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface ActionGetInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Action\Get
 */
interface ActionGetInputPort
{
    /**
     * Get information for specified entity
     * @param ActionGetRequestModel $requestModel
     * @return ViewModel
     */
    public function get(ActionGetRequestModel $requestModel): ViewModel;
}
