<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Delete;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskActionDeleteInputPort
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Delete
 */
interface TaskActionDeleteInputPort
{
    /**
     * Delete binding between given task and action
     * @param TaskActionDeleteRequestModel $requestModel
     * @return ViewModel
     */
    public function delete(TaskActionDeleteRequestModel $requestModel): ViewModel;
}
