<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Restore;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskRestoreInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Task\Restore
 */
interface TaskRestoreInputPort
{
    /**
     * Restore information for specified entity
     * @param TaskRestoreRequestModel $requestModel
     * @return ViewModel
     */
    public function restore(TaskRestoreRequestModel $requestModel): ViewModel;
}
