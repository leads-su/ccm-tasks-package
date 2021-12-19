<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Get;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineTaskGetInputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Get
 */
interface PipelineTaskGetInputPort
{
    /**
     * Get information for specific pipeline task
     * @param PipelineTaskGetRequestModel $requestModel
     * @return ViewModel
     */
    public function information(PipelineTaskGetRequestModel $requestModel): ViewModel;
}
