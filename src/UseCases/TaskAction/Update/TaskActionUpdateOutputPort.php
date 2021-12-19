<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Update;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskActionUpdateOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Update
 */
interface TaskActionUpdateOutputPort
{
    /**
     * Output port for "update"
     * @param TaskActionUpdateResponseModel $responseModel
     * @return ViewModel
     */
    public function update(TaskActionUpdateResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param TaskActionUpdateResponseModel $responseModel
     * @param string $model
     * @return ViewModel
     */
    public function notFound(TaskActionUpdateResponseModel $responseModel, string $model): ViewModel;

    /**
     * Output port for "internal server error"
     * @param TaskActionUpdateResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(TaskActionUpdateResponseModel $responseModel, Throwable $exception): ViewModel;
}
