<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\Delete;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelinePermissionDeleteInputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\Delete
 */
interface PipelinePermissionDeleteInputPort
{
    /**
     * Input port for "delete"
     * @param PipelinePermissionDeleteRequestModel $requestModel
     * @return ViewModel
     */
    public function delete(PipelinePermissionDeleteRequestModel $requestModel): ViewModel;
}
