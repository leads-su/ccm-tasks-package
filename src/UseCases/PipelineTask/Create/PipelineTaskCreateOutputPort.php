<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Create;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineTaskCreateOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Create
 */
interface PipelineTaskCreateOutputPort
{
    /**
     * Output port for "create"
     * @param PipelineTaskCreateResponseModel $responseModel
     * @return ViewModel
     */
    public function create(PipelineTaskCreateResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param PipelineTaskCreateResponseModel $responseModel
     * @param string $model
     * @return ViewModel
     */
    public function notFound(PipelineTaskCreateResponseModel $responseModel, string $model): ViewModel;

    /**
     * Output port for "already exists"
     * @param PipelineTaskCreateResponseModel $responseModel
     * @param string $model
     * @return ViewModel
     */
    public function alreadyExists(PipelineTaskCreateResponseModel $responseModel, string $model): ViewModel;

    /**
     * Output port for "internal server error"
     * @param PipelineTaskCreateResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(PipelineTaskCreateResponseModel $responseModel, Throwable $exception): ViewModel;
}
