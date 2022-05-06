<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Update;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskUpdateOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Task\Update
 */
interface TaskUpdateOutputPort
{
    /**
     * Output port for "update"
     * @param TaskUpdateResponseModel $responseModel
     * @return ViewModel
     */
    public function update(TaskUpdateResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param TaskUpdateResponseModel $responseModel
     * @return ViewModel
     */
    public function notFound(TaskUpdateResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param TaskUpdateResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(TaskUpdateResponseModel $responseModel, Throwable $exception): ViewModel;
}
