<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskListOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Task\List
 */
interface TaskListOutputPort
{
    /**
     * Output port for "list"
     * @param TaskListResponseModel $responseModel
     * @return ViewModel
     */
    public function list(TaskListResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param TaskListResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(TaskListResponseModel $responseModel, Throwable $exception): ViewModel;
}
