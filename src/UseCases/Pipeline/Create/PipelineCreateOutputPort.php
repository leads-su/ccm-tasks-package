<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Create;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineCreateOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Create
 */
interface PipelineCreateOutputPort
{
    /**
     * Output port for "create"
     * @param PipelineCreateResponseModel $responseModel
     * @return ViewModel
     */
    public function create(PipelineCreateResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelineCreateResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(PipelineCreateResponseModel $responseModel, Throwable $exception): ViewModel;
}
