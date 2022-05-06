<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\List;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineTaskListInputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\List
 */
interface PipelineTaskListInputPort
{
    /**
     * Get list of all available pipeline tasks
     * @param PipelineTaskListRequestModel $requestModel
     * @return ViewModel
     */
    public function list(PipelineTaskListRequestModel $requestModel): ViewModel;
}
