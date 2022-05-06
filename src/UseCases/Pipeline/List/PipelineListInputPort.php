<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\List;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineListInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\List
 */
interface PipelineListInputPort
{
    /**
     * Get list of all available pipelines
     * @param PipelineListRequestModel $requestModel
     * @return ViewModel
     */
    public function list(PipelineListRequestModel $requestModel): ViewModel;
}
