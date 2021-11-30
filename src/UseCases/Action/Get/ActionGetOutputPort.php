<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Get;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface ActionGetOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Action\Get
 */
interface ActionGetOutputPort
{
    /**
     * Output port for "get"
     * @param ActionGetResponseModel $responseModel
     * @return ViewModel
     */
    public function get(ActionGetResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param ActionGetResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(ActionGetResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param ActionGetResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(ActionGetResponseModel $responseModel, Throwable $exception): ViewModel;
}
