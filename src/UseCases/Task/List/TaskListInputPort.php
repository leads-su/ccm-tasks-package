<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\List;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskListInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Task\List
 */
interface TaskListInputPort
{
    /**
     * Get list of all available tasks
     * @param TaskListRequestModel $requestModel
     * @return ViewModel
     */
    public function list(TaskListRequestModel $requestModel): ViewModel;
}
