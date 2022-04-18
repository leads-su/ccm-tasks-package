<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelinePermissionListOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\List
 */
interface PipelinePermissionListOutputPort
{
    /**
     * Output port for "list"
     * @param PipelinePermissionListResponseModel $responseModel
     * @return ViewModel
     */
    public function list(PipelinePermissionListResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param PipelinePermissionListResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(PipelinePermissionListResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelinePermissionListResponseModel $responseModel
     * @param Throwable $throwable
     * @return ViewModel
     */
    public function internalServerError(PipelinePermissionListResponseModel $responseModel, Throwable $throwable): ViewModel;
}
