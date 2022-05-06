<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineHistory\List;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineHistoryListInputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineHistory\List
 */
interface PipelineHistoryListInputPort
{
    /**
     * Input port for "list"
     * @param PipelineHistoryListRequestModel $requestModel
     * @return ViewModel
     */
    public function list(PipelineHistoryListRequestModel $requestModel): ViewModel;
}
