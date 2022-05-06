<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Restore;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface ActionRestoreInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Action\Restore
 */
interface ActionRestoreInputPort
{
    /**
     * Restore information for specified entity
     * @param ActionRestoreRequestModel $requestModel
     * @return ViewModel
     */
    public function restore(ActionRestoreRequestModel $requestModel): ViewModel;
}
