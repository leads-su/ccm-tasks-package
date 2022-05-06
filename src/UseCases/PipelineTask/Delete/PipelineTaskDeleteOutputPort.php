<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Delete;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineTaskDeleteOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Delete
 */
interface PipelineTaskDeleteOutputPort
{
    /**
     * Output port for "delete"
     * @param PipelineTaskDeleteResponseModel $responseModel
     * @return ViewModel
     */
    public function delete(PipelineTaskDeleteResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param PipelineTaskDeleteResponseModel $responseModel
     * @param string $model
     * @return ViewModel
     */
    public function notFound(PipelineTaskDeleteResponseModel $responseModel, string $model): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelineTaskDeleteResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(PipelineTaskDeleteResponseModel $responseModel, Throwable $exception): ViewModel;
}
