<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Update;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineTaskUpdateInputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Update
 */
interface PipelineTaskUpdateInputPort
{
    /**
     * Update pipeline task binding
     * @param PipelineTaskUpdateRequestModel $requestModel
     * @return ViewModel
     */
    public function update(PipelineTaskUpdateRequestModel $requestModel): ViewModel;
}
