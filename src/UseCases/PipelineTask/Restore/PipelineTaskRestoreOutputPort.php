<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Restore;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineTaskRestoreOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Restore
 */
interface PipelineTaskRestoreOutputPort
{
    /**
     * Output port for "restore"
     * @param PipelineTaskRestoreResponseModel $responseModel
     * @return ViewModel
     */
    public function restore(PipelineTaskRestoreResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param PipelineTaskRestoreResponseModel $responseModel
     * @param string $model
     * @return ViewModel
     */
    public function notFound(PipelineTaskRestoreResponseModel $responseModel, string $model): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelineTaskRestoreResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(PipelineTaskRestoreResponseModel $responseModel, Throwable $exception): ViewModel;
}
