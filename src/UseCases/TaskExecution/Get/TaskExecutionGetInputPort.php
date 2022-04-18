<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskExecution\Get;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskExecutionGetInputPort
 * @package ConsulConfigManager\Tasks\UseCases\TaskExecution\Get
 */
interface TaskExecutionGetInputPort
{
    /**
     * Input port for "get"
     * @param TaskExecutionGetRequestModel $requestModel
     * @return ViewModel
     */
    public function get(TaskExecutionGetRequestModel $requestModel): ViewModel;
}
