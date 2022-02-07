<?php

namespace ConsulConfigManager\Tasks\UseCases\ActionExecution\Get;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface ActionExecutionGetOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\ActionExecution\Get
 */
interface ActionExecutionGetOutputPort
{
    /**
     * Output port for "get"
     * @param ActionExecutionGetResponseModel $responseModel
     * @return ViewModel
     */
    public function get(ActionExecutionGetResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param ActionExecutionGetResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(ActionExecutionGetResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param ActionExecutionGetResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(ActionExecutionGetResponseModel $responseModel, Throwable $exception): ViewModel;
}
