<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Update;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineTaskUpdateOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Update
 */
interface PipelineTaskUpdateOutputPort
{
    /**
     * Output port for "update"
     * @param PipelineTaskUpdateResponseModel $responseModel
     * @return ViewModel
     */
    public function update(PipelineTaskUpdateResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param PipelineTaskUpdateResponseModel $responseModel
     * @param string $model
     * @return ViewModel
     */
    public function notFound(PipelineTaskUpdateResponseModel $responseModel, string $model): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelineTaskUpdateResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(PipelineTaskUpdateResponseModel $responseModel, Throwable $exception): ViewModel;
}
