<?php

namespace ConsulConfigManager\Tasks\UseCases\Service\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface ServiceListOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Service\List
 */
interface ServiceListOutputPort
{
    /**
     * Output port for "list"
     * @param ServiceListResponseModel $responseModel
     * @return ViewModel
     */
    public function list(ServiceListResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param ServiceListResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(ServiceListResponseModel $responseModel, Throwable $exception): ViewModel;
}
