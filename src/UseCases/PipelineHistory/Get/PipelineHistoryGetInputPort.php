<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineHistory\Get;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineHistoryGetInputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineHistory\Get
 */
interface PipelineHistoryGetInputPort
{
    /**
     * Input port for "get"
     * @param PipelineHistoryGetRequestModel $requestModel
     * @return ViewModel
     */
    public function get(PipelineHistoryGetRequestModel $requestModel): ViewModel;
}
