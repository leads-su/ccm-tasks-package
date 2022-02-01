<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineExecution\List;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineExecutionListInputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineExecution\List
 */
interface PipelineExecutionListInputPort {

    /**
     * Input port for "list"
     * @param PipelineExecutionListRequestModel $requestModel
     * @return ViewModel
     */
    public function list(PipelineExecutionListRequestModel $requestModel): ViewModel;

}
