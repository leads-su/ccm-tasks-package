<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Delete;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskActionDeleteOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Delete
 */
interface TaskActionDeleteOutputPort
{
    /**
     * Output port for "delete"
     * @param TaskActionDeleteResponseModel $responseModel
     * @return ViewModel
     */
    public function delete(TaskActionDeleteResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param TaskActionDeleteResponseModel $responseModel
     * @param string $model
     * @return ViewModel
     */
    public function notFound(TaskActionDeleteResponseModel $responseModel, string $model): ViewModel;

    /**
     * Output port for "internal server error"
     * @param TaskActionDeleteResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(TaskActionDeleteResponseModel $responseModel, Throwable $exception): ViewModel;
}
