<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Delete;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface ActionDeleteOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Action\Delete
 */
interface ActionDeleteOutputPort
{
    /**
     * Output port for "delete"
     * @param ActionDeleteResponseModel $responseModel
     * @return ViewModel
     */
    public function delete(ActionDeleteResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param ActionDeleteResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(ActionDeleteResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param ActionDeleteResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(ActionDeleteResponseModel $responseModel, Throwable $exception): ViewModel;
}
