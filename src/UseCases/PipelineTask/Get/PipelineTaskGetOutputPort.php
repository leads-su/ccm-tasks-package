<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Get;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineTaskGetOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Get
 */
interface PipelineTaskGetOutputPort
{
    /**
     * Output port for "information"
     * @param PipelineTaskGetResponseModel $responseModel
     * @return ViewModel
     */
    public function information(PipelineTaskGetResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param PipelineTaskGetResponseModel $responseModel
     * @param string $model
     * @return ViewModel
     */
    public function notFound(PipelineTaskGetResponseModel $responseModel, string $model): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelineTaskGetResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(PipelineTaskGetResponseModel $responseModel, Throwable $exception): ViewModel;
}
