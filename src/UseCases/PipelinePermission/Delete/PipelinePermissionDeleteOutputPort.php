<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\Delete;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelinePermissionDeleteOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\Delete
 */
interface PipelinePermissionDeleteOutputPort
{
    /**
     * Output port for "delete"
     * @param PipelinePermissionDeleteResponseModel $responseModel
     * @return ViewModel
     */
    public function delete(PipelinePermissionDeleteResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param PipelinePermissionDeleteResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(PipelinePermissionDeleteResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelinePermissionDeleteResponseModel $responseModel
     * @param Throwable $throwable
     * @return ViewModel
     */
    public function internalServerError(PipelinePermissionDeleteResponseModel $responseModel, Throwable $throwable): ViewModel;
}
