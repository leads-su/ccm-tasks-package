<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineHistory\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineHistoryListOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineHistory\List
 */
interface PipelineHistoryListOutputPort
{
    /**
     * Output port for "list"
     * @param PipelineHistoryListResponseModel $responseModel
     * @return ViewModel
     */
    public function list(PipelineHistoryListResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelineHistoryListResponseModel $responseModel
     * @param Throwable $throwable
     * @return ViewModel
     */
    public function internalServerError(PipelineHistoryListResponseModel $responseModel, Throwable $throwable): ViewModel;
}
