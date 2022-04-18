<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineHistory\Get;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineHistoryGetOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineHistory\Get
 */
interface PipelineHistoryGetOutputPort
{
    /**
     * Output port for "get"
     * @param PipelineHistoryGetResponseModel $responseModel
     * @return ViewModel
     */
    public function get(PipelineHistoryGetResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param PipelineHistoryGetResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(PipelineHistoryGetResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelineHistoryGetResponseModel $responseModel
     * @param Throwable $throwable
     * @return ViewModel
     */
    public function internalServerError(PipelineHistoryGetResponseModel $responseModel, Throwable $throwable): ViewModel;
}
