<?php

namespace ConsulConfigManager\Tasks\UseCases\Service\List;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface ServiceListInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Service\List
 */
interface ServiceListInputPort
{
    /**
     * Get list of all available services
     * @param ServiceListRequestModel $requestModel
     * @return ViewModel
     */
    public function list(ServiceListRequestModel $requestModel): ViewModel;
}
