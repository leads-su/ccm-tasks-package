<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Restore;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskActionRestoreOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Restore
 */
interface TaskActionRestoreOutputPort
{
    /**
     * Output port for "restore"
     * @param TaskActionRestoreResponseModel $responseModel
     * @return ViewModel
     */
    public function restore(TaskActionRestoreResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param TaskActionRestoreResponseModel $responseModel
     * @param string $model
     * @return ViewModel
     */
    public function notFound(TaskActionRestoreResponseModel $responseModel, string $model): ViewModel;

    /**
     * Output port for "internal server error"
     * @param TaskActionRestoreResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(TaskActionRestoreResponseModel $responseModel, Throwable $exception): ViewModel;
}
