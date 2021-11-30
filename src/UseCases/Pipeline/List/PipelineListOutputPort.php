<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineListOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\List
 */
interface PipelineListOutputPort
{
    /**
     * Output port for "list"
     * @param PipelineListResponseModel $responseModel
     * @return ViewModel
     */
    public function list(PipelineListResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelineListResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(PipelineListResponseModel $responseModel, Throwable $exception): ViewModel;
}
