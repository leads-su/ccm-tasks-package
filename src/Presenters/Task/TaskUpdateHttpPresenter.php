<?php

namespace ConsulConfigManager\Tasks\Presenters\Task;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Task\Update\TaskUpdateOutputPort;
use ConsulConfigManager\Tasks\UseCases\Task\Update\TaskUpdateResponseModel;

/**
 * Class TaskUpdateHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\Task
 */
class TaskUpdateHttpPresenter implements TaskUpdateOutputPort
{
    /**
     * @inheritDoc
     */
    public function update(TaskUpdateResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntity()->toArray(),
            'Successfully updated task',
            Response::HTTP_CREATED,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(TaskUpdateResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_error(
            [],
            'Unable to find task with specified identifier'
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(TaskUpdateResponseModel $responseModel, Throwable $exception): ViewModel
    {
        if (config('app.debug')) {
            throw $exception;
        }

        return new HttpResponseViewModel(response_error(
            $exception,
            'Unable to update task',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreStart
}
