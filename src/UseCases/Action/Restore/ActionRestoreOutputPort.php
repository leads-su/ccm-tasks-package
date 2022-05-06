<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Restore;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface ActionRestoreOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Action\Restore
 */
interface ActionRestoreOutputPort
{
    /**
     * Output port for "restore"
     * @param ActionRestoreResponseModel $responseModel
     * @return ViewModel
     */
    public function restore(ActionRestoreResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param ActionRestoreResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(ActionRestoreResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param ActionRestoreResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(ActionRestoreResponseModel $responseModel, Throwable $exception): ViewModel;
}
