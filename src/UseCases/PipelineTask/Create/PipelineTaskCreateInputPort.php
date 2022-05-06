<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Create;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineTaskCreateInputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Create
 */
interface PipelineTaskCreateInputPort
{
    /**
     * Create pipeline task binding
     * @param PipelineTaskCreateRequestModel $requestModel
     * @return ViewModel
     */
    public function create(PipelineTaskCreateRequestModel $requestModel): ViewModel;
}
