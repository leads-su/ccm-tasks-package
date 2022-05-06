<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Delete;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskDeleteOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Task\Delete
 */
interface TaskDeleteOutputPort
{
    /**
     * Output port for "delete"
     * @param TaskDeleteResponseModel $responseModel
     * @return ViewModel
     */
    public function delete(TaskDeleteResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param TaskDeleteResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(TaskDeleteResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param TaskDeleteResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(TaskDeleteResponseModel $responseModel, Throwable $exception): ViewModel;
}
