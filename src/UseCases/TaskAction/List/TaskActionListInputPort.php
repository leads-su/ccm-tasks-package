<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\List;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskActionListInputPort
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\List
 */
interface TaskActionListInputPort
{
    /**
     * Get list of all available task actions
     * @param TaskActionListRequestModel $requestModel
     * @return ViewModel
     */
    public function list(TaskActionListRequestModel $requestModel): ViewModel;
}
