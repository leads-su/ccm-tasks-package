<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\Users;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelinePermissionUsersInputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\Users
 */
interface PipelinePermissionUsersInputPort
{
    /**
     * Input port for "users"
     * @param PipelinePermissionUsersRequestModel $requestModel
     * @return ViewModel
     */
    public function users(PipelinePermissionUsersRequestModel $requestModel): ViewModel;
}
