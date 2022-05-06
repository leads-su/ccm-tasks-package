<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Update;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineUpdateInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Update
 */
interface PipelineUpdateInputPort
{
    /**
     * Update pipeline
     * @param PipelineUpdateRequestModel $requestModel
     * @return ViewModel
     */
    public function update(PipelineUpdateRequestModel $requestModel): ViewModel;
}
