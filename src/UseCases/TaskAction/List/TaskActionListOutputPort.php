<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskActionListOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\List
 */
interface TaskActionListOutputPort
{
    /**
     * Output port for "list"
     * @param TaskActionListResponseModel $responseModel
     * @return ViewModel
     */
    public function list(TaskActionListResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param TaskActionListResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(TaskActionListResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param TaskActionListResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(TaskActionListResponseModel $responseModel, Throwable $exception): ViewModel;
}
