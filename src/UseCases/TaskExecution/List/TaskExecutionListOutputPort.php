<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskExecution\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskExecutionListOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\TaskExecution\List
 */
interface TaskExecutionListOutputPort
{
    /**
     * Output port for "list"
     * @param TaskExecutionListResponseModel $responseModel
     * @return ViewModel
     */
    public function list(TaskExecutionListResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param TaskExecutionListResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(TaskExecutionListResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param TaskExecutionListResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(TaskExecutionListResponseModel $responseModel, Throwable $exception): ViewModel;
}
