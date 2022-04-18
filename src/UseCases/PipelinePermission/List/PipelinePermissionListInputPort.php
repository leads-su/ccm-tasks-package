<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\List;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelinePermissionListInputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\List
 */
interface PipelinePermissionListInputPort
{
    /**
     * Input port for "list"
     * @param PipelinePermissionListRequestModel $requestModel
     * @return ViewModel
     */
    public function list(PipelinePermissionListRequestModel $requestModel): ViewModel;
}
