<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Create;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskActionCreateOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Create
 */
interface TaskActionCreateOutputPort
{
    /**
     * Output port for "create"
     * @param TaskActionCreateResponseModel $responseModel
     * @return ViewModel
     */
    public function create(TaskActionCreateResponseModel $responseModel): ViewModel;

    /**
     * Output port for "not found"
     * @param TaskActionCreateResponseModel $responseModel
     * @param string $model
     * @return ViewModel
     */
    public function notFound(TaskActionCreateResponseModel $responseModel, string $model): ViewModel;

    /**
     * Output port for "internal server error"
     * @param TaskActionCreateResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(TaskActionCreateResponseModel $responseModel, Throwable $exception): ViewModel;
}
