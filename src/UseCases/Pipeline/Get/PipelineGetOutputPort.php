<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Get;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineGetOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Get
 */
interface PipelineGetOutputPort
{
    /**
     * Output port for "get"
     * @param PipelineGetResponseModel $responseModel
     * @return ViewModel
     */
    public function get(PipelineGetResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param PipelineGetResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(PipelineGetResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelineGetResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(PipelineGetResponseModel $responseModel, Throwable $exception): ViewModel;
}
