<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Create;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface ActionCreateOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Action\Create
 */
interface ActionCreateOutputPort
{
    /**
     * Output port for "create"
     * @param ActionCreateResponseModel $responseModel
     * @return ViewModel
     */
    public function create(ActionCreateResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param ActionCreateResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(ActionCreateResponseModel $responseModel, Throwable $exception): ViewModel;
}
