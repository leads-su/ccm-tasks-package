<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Restore;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineTaskRestoreInputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Restore
 */
interface PipelineTaskRestoreInputPort
{
    /**
     * Restore binding between given task and action
     * @param PipelineTaskRestoreRequestModel $requestModel
     * @return ViewModel
     */
    public function restore(PipelineTaskRestoreRequestModel $requestModel): ViewModel;
}
