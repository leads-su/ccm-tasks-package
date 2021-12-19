<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Delete;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineTaskDeleteInputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Delete
 */
interface PipelineTaskDeleteInputPort
{
    /**
     * Delete binding between given task and action
     * @param PipelineTaskDeleteRequestModel $requestModel
     * @return ViewModel
     */
    public function delete(PipelineTaskDeleteRequestModel $requestModel): ViewModel;
}
