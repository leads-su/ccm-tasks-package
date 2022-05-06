<?php

namespace ConsulConfigManager\Tasks\Presenters\Task;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Task\Create\TaskCreateOutputPort;
use ConsulConfigManager\Tasks\UseCases\Task\Create\TaskCreateResponseModel;

/**
 * Class TaskCreateHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\Task
 */
class TaskCreateHttpPresenter implements TaskCreateOutputPort
{
    /**
     * @inheritDoc
     */
    public function create(TaskCreateResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntity()->toArray(),
            'Successfully created new task',
            Response::HTTP_CREATED,
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(TaskCreateResponseModel $responseModel, Throwable $exception): ViewModel
    {
        if (config('app.debug')) {
            throw $exception;
        }

        return new HttpResponseViewModel(response_error(
            $exception,
            'Unable to create new task',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreStart
}
