<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Update;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskUpdateInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Task\Update
 */
interface TaskUpdateInputPort
{
    /**
     * Update task
     * @param TaskUpdateRequestModel $requestModel
     * @return ViewModel
     */
    public function update(TaskUpdateRequestModel $requestModel): ViewModel;
}
