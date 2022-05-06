<?php

namespace ConsulConfigManager\Tasks\Presenters\Task;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Task\List\TaskListOutputPort;
use ConsulConfigManager\Tasks\UseCases\Task\List\TaskListResponseModel;

/**
 * Class TaskListHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\Task
 */
class TaskListHttpPresenter implements TaskListOutputPort
{
    /**
     * @inheritDoc
     */
    public function list(TaskListResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntities(),
            'Successfully fetched list of tasks',
            Response::HTTP_OK,
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(TaskListResponseModel $responseModel, Throwable $exception): ViewModel
    {
        if (config('app.debug')) {
            throw $exception;
        }

        return new HttpResponseViewModel(response_error(
            $exception,
            'Unable to retrieve tasks list',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreEnd
}
