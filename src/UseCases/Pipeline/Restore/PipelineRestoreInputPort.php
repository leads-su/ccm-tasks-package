<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Restore;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineRestoreInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Restore
 */
interface PipelineRestoreInputPort
{
    /**
     * Restore information for specified entity
     * @param PipelineRestoreRequestModel $requestModel
     * @return ViewModel
     */
    public function restore(PipelineRestoreRequestModel $requestModel): ViewModel;
}
