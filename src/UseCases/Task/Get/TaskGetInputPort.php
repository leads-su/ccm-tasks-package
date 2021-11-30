<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Get;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskGetInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Task\Get
 */
interface TaskGetInputPort
{
    /**
     * Get information for specified entity
     * @param TaskGetRequestModel $requestModel
     * @return ViewModel
     */
    public function get(TaskGetRequestModel $requestModel): ViewModel;
}
