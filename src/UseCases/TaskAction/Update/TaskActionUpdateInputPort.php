<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Update;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskActionUpdateInputPort
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Update
 */
interface TaskActionUpdateInputPort
{
    /**
     * Update task action binding
     * @param TaskActionUpdateRequestModel $requestModel
     * @return ViewModel
     */
    public function update(TaskActionUpdateRequestModel $requestModel): ViewModel;
}
