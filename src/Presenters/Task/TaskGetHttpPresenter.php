<?php

namespace ConsulConfigManager\Tasks\Presenters\Task;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Task\Get\TaskGetOutputPort;
use ConsulConfigManager\Tasks\UseCases\Task\Get\TaskGetResponseModel;

/**
 * Class TaskGetHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\Task
 */
class TaskGetHttpPresenter implements TaskGetOutputPort
{
    /**
     * @inheritDoc
     */
    public function get(TaskGetResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntity(),
            'Successfully fetched task information',
            Response::HTTP_OK,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(TaskGetResponseModel $responseModel): ViewModel
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
    public function internalServerError(TaskGetResponseModel $responseModel, Throwable $exception): ViewModel
    {
        if (config('app.debug')) {
            throw $exception;
        }

        return new HttpResponseViewModel(response_error(
            $exception,
            'Unable to retrieve tasks information',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreEnd
}
