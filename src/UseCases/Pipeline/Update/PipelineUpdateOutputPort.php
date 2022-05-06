<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Update;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineUpdateOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Update
 */
interface PipelineUpdateOutputPort
{
    /**
     * Output port for "update"
     * @param PipelineUpdateResponseModel $responseModel
     * @return ViewModel
     */
    public function update(PipelineUpdateResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param PipelineUpdateResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(PipelineUpdateResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelineUpdateResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(PipelineUpdateResponseModel $responseModel, Throwable $exception): ViewModel;
}
