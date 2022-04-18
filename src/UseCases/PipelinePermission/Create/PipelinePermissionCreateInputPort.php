<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\Create;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelinePermissionCreateInputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\Create
 */
interface PipelinePermissionCreateInputPort
{
    /**
     * Input port for "create"
     * @param PipelinePermissionCreateRequestModel $requestModel
     * @return ViewModel
     */
    public function create(PipelinePermissionCreateRequestModel $requestModel): ViewModel;
}
