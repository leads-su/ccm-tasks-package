<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Create;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskCreateInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Task\Create
 */
interface TaskCreateInputPort
{
    /**
     * Create task
     * @param TaskCreateRequestModel $requestModel
     * @return ViewModel
     */
    public function create(TaskCreateRequestModel $requestModel): ViewModel;
}
