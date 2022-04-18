<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\Users;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelinePermissionUsersOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\Users
 */
interface PipelinePermissionUsersOutputPort
{
    /**
     * Output port for "users"
     * @param PipelinePermissionUsersResponseModel $responseModel
     * @return ViewModel
     */
    public function users(PipelinePermissionUsersResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelinePermissionUsersResponseModel $responseModel
     * @param Throwable $throwable
     * @return ViewModel
     */
    public function internalServerError(PipelinePermissionUsersResponseModel $responseModel, Throwable $throwable): ViewModel;
}
