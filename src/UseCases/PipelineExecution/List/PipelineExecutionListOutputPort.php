<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineExecution\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineExecutionListOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineExecution\List
 */
interface PipelineExecutionListOutputPort
{
    /**
     * Output port for "list"
     * @param PipelineExecutionListResponseModel $responseModel
     * @return ViewModel
     */
    public function list(PipelineExecutionListResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelineExecutionListResponseModel $responseModel
     * @param Throwable $throwable
     * @return ViewModel
     */
    public function internalServerError(PipelineExecutionListResponseModel $responseModel, Throwable $throwable): ViewModel;
}
