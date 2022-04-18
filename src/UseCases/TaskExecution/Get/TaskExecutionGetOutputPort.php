<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskExecution\Get;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskExecutionGetOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\TaskExecution\Get
 */
interface TaskExecutionGetOutputPort
{
    /**
     * Output port for "get"
     * @param TaskExecutionGetResponseModel $responseModel
     * @return ViewModel
     */
    public function get(TaskExecutionGetResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param TaskExecutionGetResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(TaskExecutionGetResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param TaskExecutionGetResponseModel $responseModel
     * @param Throwable $throwable
     * @return ViewModel
     */
    public function internalServerError(TaskExecutionGetResponseModel $responseModel, Throwable $throwable): ViewModel;
}
