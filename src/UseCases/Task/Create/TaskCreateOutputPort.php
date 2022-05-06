<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Create;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface TaskCreateOutputPort
 * @package ConsulConfigManager\Tasks\UseCases\Task\Create
 */
interface TaskCreateOutputPort
{
    /**
     * Output port for "create"
     * @param TaskCreateResponseModel $responseModel
     * @return ViewModel
     */
    public function create(TaskCreateResponseModel $responseModel): ViewModel;

    /**
     * Output port for "internal server error"
     * @param TaskCreateResponseModel $responseModel
     * @param Throwable $exception
     * @return ViewModel
     */
    public function internalServerError(TaskCreateResponseModel $responseModel, Throwable $exception): ViewModel;
}
