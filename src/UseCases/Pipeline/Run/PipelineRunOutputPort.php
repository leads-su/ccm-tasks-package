<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Run;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineRunOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Run
 */
interface PipelineRunOutputPort
{
    /**
     * Output port for "run"
     * @param PipelineRunResponseModel $responseModel
     * @return ViewModel
     */
    public function run(PipelineRunResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param PipelineRunResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(PipelineRunResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelineRunResponseModel $responseModel
     * @param Throwable $throwable
     * @return ViewModel
     */
    public function internalServerError(PipelineRunResponseModel $responseModel, Throwable $throwable): ViewModel;
}
