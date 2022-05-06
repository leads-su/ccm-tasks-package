<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Restore;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskRestoreOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Task\Restore
 */
interface TaskRestoreOutputPort
{
    /**
     * Output port for "restore"
     * @param TaskRestoreResponseModel $responseModel
     * @return ViewModel
     */
    public function restore(TaskRestoreResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param TaskRestoreResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(TaskRestoreResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param TaskRestoreResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(TaskRestoreResponseModel $responseModel, Throwable $exception): ViewModel;
}
