<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineExecution\Get;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineExecutionGetOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineExecution\Get
 */
interface PipelineExecutionGetOutputPort {

    /**
     * Output port for "list"
     * @param PipelineExecutionGetResponseModel $responseModel
     * @return ViewModel
     */
    public function list(PipelineExecutionGetResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param PipelineExecutionGetResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(PipelineExecutionGetResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelineExecutionGetResponseModel $responseModel
     * @param Throwable $throwable
     * @return ViewModel
     */
    public function internalServerError(PipelineExecutionGetResponseModel $responseModel, Throwable $throwable): ViewModel;

}
