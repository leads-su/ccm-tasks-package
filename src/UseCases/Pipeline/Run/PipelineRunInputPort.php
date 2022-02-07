<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Run;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineRunInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Run
 */
interface PipelineRunInputPort
{
    /**
     * Input port for "run"
     * @param PipelineRunRequestModel $requestModel
     * @return ViewModel
     */
    public function run(PipelineRunRequestModel $requestModel): ViewModel;
}
