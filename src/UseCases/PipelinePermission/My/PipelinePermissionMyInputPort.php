<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\My;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelinePermissionMyInputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\My
 */
interface PipelinePermissionMyInputPort
{
    /**
     * Input port for "my"
     * @param PipelinePermissionMyRequestModel $requestModel
     * @return ViewModel
     */
    public function my(PipelinePermissionMyRequestModel $requestModel): ViewModel;
}
