<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Update;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface ActionUpdateOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Action\Update
 */
interface ActionUpdateOutputPort
{
    /**
     * Output port for "update"
     * @param ActionUpdateResponseModel $responseModel
     * @return ViewModel
     */
    public function update(ActionUpdateResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param ActionUpdateResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(ActionUpdateResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param ActionUpdateResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(ActionUpdateResponseModel $responseModel, Throwable $exception): ViewModel;
}
