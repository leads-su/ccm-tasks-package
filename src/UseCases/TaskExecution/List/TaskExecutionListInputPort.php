<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskExecution\List;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskExecutionListInputPort
 * @package ConsulConfigManager\Tasks\UseCases\TaskExecution\List
 */
interface TaskExecutionListInputPort
{
    /**
     * Input port for "list"
     * @param TaskExecutionListRequestModel $requestModel
     * @return ViewModel
     */
    public function list(TaskExecutionListRequestModel $requestModel): ViewModel;
}
