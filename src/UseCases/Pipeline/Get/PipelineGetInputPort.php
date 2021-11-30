<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Get;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineGetInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Get
 */
interface PipelineGetInputPort
{
    /**
     * Get information for specified entity
     * @param PipelineGetRequestModel $requestModel
     * @return ViewModel
     */
    public function get(PipelineGetRequestModel $requestModel): ViewModel;
}
