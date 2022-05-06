<?php

namespace ConsulConfigManager\Tasks\UseCases\ActionExecution\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface ActionExecutionListOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\ActionExecution\List
 */
interface ActionExecutionListOutputPort
{
    /**
     * Output port for "list"
     * @param ActionExecutionListResponseModel $responseModel
     * @return ViewModel
     */
    public function list(ActionExecutionListResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param ActionExecutionListResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(ActionExecutionListResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param ActionExecutionListResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(ActionExecutionListResponseModel $responseModel, Throwable $exception): ViewModel;
}
