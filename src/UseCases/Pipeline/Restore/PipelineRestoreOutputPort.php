<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Restore;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineRestoreOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Restore
 */
interface PipelineRestoreOutputPort
{
    /**
     * Output port for "restore"
     * @param PipelineRestoreResponseModel $responseModel
     * @return ViewModel
     */
    public function restore(PipelineRestoreResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param PipelineRestoreResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(PipelineRestoreResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelineRestoreResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(PipelineRestoreResponseModel $responseModel, Throwable $exception): ViewModel;
}
