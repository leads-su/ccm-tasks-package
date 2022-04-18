<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\My;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelinePermissionMyOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\My
 */
interface PipelinePermissionMyOutputPort
{
    /**
     * Output port for "my"
     * @param PipelinePermissionMyResponseModel $responseModel
     * @return ViewModel
     */
    public function my(PipelinePermissionMyResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelinePermissionMyResponseModel $responseModel
     * @param Throwable $throwable
     * @return ViewModel
     */
    public function internalServerError(PipelinePermissionMyResponseModel $responseModel, Throwable $throwable): ViewModel;
}
