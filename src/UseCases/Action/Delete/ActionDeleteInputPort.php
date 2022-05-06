<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Delete;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface ActionDeleteInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Action\Delete
 */
interface ActionDeleteInputPort
{
    /**
     * Delete information for specified entity
     * @param ActionDeleteRequestModel $requestModel
     * @return ViewModel
     */
    public function delete(ActionDeleteRequestModel $requestModel): ViewModel;
}
