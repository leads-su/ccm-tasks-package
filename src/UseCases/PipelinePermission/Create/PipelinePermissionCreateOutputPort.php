<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\Create;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelinePermissionCreateOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\Create
 */
interface PipelinePermissionCreateOutputPort
{
    /**
     * Output port for "create"
     * @param PipelinePermissionCreateResponseModel $responseModel
     * @return ViewModel
     */
    public function create(PipelinePermissionCreateResponseModel $responseModel): ViewModel;

    /**
     * Output port for "already exists"
     * @param PipelinePermissionCreateResponseModel $responseModel
     * @return ViewModel
     */
    public function alreadyExists(PipelinePermissionCreateResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelinePermissionCreateResponseModel $responseModel
     * @param Throwable $throwable
     * @return ViewModel
     */
    public function internalServerError(PipelinePermissionCreateResponseModel $responseModel, Throwable $throwable): ViewModel;
}
