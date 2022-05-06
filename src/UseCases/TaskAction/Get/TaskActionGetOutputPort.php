<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Get;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskActionGetOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Get
 */
interface TaskActionGetOutputPort
{
    /**
     * Output port for "information"
     * @param TaskActionGetResponseModel $responseModel
     * @return ViewModel
     */
    public function information(TaskActionGetResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param TaskActionGetResponseModel $responseModel
     * @param string $model
     * @return ViewModel
     */
    public function notFound(TaskActionGetResponseModel $responseModel, string $model): ViewModel;

    /**
     * Output port for "internal server error"
     * @param TaskActionGetResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(TaskActionGetResponseModel $responseModel, Throwable $exception): ViewModel;
}
