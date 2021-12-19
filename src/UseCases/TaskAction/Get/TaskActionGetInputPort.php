<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Get;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskActionGetInputPort
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Get
 */
interface TaskActionGetInputPort
{
    /**
     * Get information for specific task action
     * @param TaskActionGetRequestModel $requestModel
     * @return ViewModel
     */
    public function information(TaskActionGetRequestModel $requestModel): ViewModel;
}
