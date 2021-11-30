<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Delete;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskDeleteInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Task\Delete
 */
interface TaskDeleteInputPort
{
    /**
     * Delete information for specified entity
     * @param TaskDeleteRequestModel $requestModel
     * @return ViewModel
     */
    public function delete(TaskDeleteRequestModel $requestModel): ViewModel;
}
