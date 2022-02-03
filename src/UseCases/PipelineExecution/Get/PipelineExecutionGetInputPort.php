<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineExecution\Get;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineExecutionGetInputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineExecution\Get
 */
interface PipelineExecutionGetInputPort {

    /**
     * Input port for "get"
     * @param PipelineExecutionGetRequestModel $requestModel
     * @return ViewModel
     */
    public function get(PipelineExecutionGetRequestModel $requestModel): ViewModel;

}
