<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Delete;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineDeleteOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Delete
 */
interface PipelineDeleteOutputPort
{
    /**
     * Output port for "delete"
     * @param PipelineDeleteResponseModel $responseModel
     * @return ViewModel
     */
    public function delete(PipelineDeleteResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param PipelineDeleteResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(PipelineDeleteResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelineDeleteResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(PipelineDeleteResponseModel $responseModel, Throwable $exception): ViewModel;
}
