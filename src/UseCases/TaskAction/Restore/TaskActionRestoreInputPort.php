<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Restore;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskActionRestoreInputPort
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Restore
 */
interface TaskActionRestoreInputPort
{
    /**
     * Restore binding between given task and action
     * @param TaskActionRestoreRequestModel $requestModel
     * @return ViewModel
     */
    public function restore(TaskActionRestoreRequestModel $requestModel): ViewModel;
}
