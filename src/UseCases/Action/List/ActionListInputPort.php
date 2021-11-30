<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\List;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface ActionListInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Action\List
 */
interface ActionListInputPort
{
    /**
     * Get list of all available actions
     * @param ActionListRequestModel $requestModel
     * @return ViewModel
     */
    public function list(ActionListRequestModel $requestModel): ViewModel;
}
