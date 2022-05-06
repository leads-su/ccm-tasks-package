<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Get;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskGetOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Task\Get
 */
interface TaskGetOutputPort
{
    /**
     * Output port for "get"
     * @param TaskGetResponseModel $responseModel
     * @return ViewModel
     */
    public function get(TaskGetResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param TaskGetResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(TaskGetResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param TaskGetResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(TaskGetResponseModel $responseModel, Throwable $exception): ViewModel;
}
