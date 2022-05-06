<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineTaskListOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\List
 */
interface PipelineTaskListOutputPort
{
    /**
     * Output port for "list"
     * @param PipelineTaskListResponseModel $responseModel
     * @return ViewModel
     */
    public function list(PipelineTaskListResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param PipelineTaskListResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(PipelineTaskListResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelineTaskListResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(PipelineTaskListResponseModel $responseModel, Throwable $exception): ViewModel;
}
