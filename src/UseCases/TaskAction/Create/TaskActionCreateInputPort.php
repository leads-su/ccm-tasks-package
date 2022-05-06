<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Create;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskActionCreateInputPort
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Create
 */
interface TaskActionCreateInputPort
{
    /**
     * Create task action binding
     * @param TaskActionCreateRequestModel $requestModel
     * @return ViewModel
     */
    public function create(TaskActionCreateRequestModel $requestModel): ViewModel;
}
