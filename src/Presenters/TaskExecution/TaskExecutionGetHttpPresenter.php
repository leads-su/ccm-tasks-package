<?php

namespace ConsulConfigManager\Tasks\Presenters\TaskExecution;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\TaskExecution\Get\TaskExecutionGetOutputPort;
use ConsulConfigManager\Tasks\UseCases\TaskExecution\Get\TaskExecutionGetResponseModel;

/**
 * Class TaskExecutionGetHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\TaskExecution
 */
class TaskExecutionGetHttpPresenter implements TaskExecutionGetOutputPort
{
    /**
     * @inheritDoc
     */
    public function get(TaskExecutionGetResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntity(),
            'Successfully fetched task execution information',
            Response::HTTP_OK
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(TaskExecutionGetResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_error(
            [],
            'Unable to find requested task'
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(TaskExecutionGetResponseModel $responseModel, Throwable $throwable): ViewModel
    {
        if (config('app.debug')) {
            throw $throwable;
        }

        return new HttpResponseViewModel(response_error(
            $throwable,
            'Unable to fetch task execution information',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreEnd
}
