<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface ActionListOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Action\List
 */
interface ActionListOutputPort
{
    /**
     * Output port for "list"
     * @param ActionListResponseModel $responseModel
     * @return ViewModel
     */
    public function list(ActionListResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param ActionListResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(ActionListResponseModel $responseModel, Throwable $exception): ViewModel;
}
