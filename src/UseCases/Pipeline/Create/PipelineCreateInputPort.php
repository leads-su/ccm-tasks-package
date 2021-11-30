<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Create;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineCreateInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Create
 */
interface PipelineCreateInputPort
{
    /**
     * Create pipeline
     * @param PipelineCreateRequestModel $requestModel
     * @return ViewModel
     */
    public function create(PipelineCreateRequestModel $requestModel): ViewModel;
}
