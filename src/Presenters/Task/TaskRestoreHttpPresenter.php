<?php

namespace ConsulConfigManager\Tasks\Presenters\Task;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Task\Restore\TaskRestoreOutputPort;
use ConsulConfigManager\Tasks\UseCases\Task\Restore\TaskRestoreResponseModel;

/**
 * Class TaskRestoreHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\Task
 */
class TaskRestoreHttpPresenter implements TaskRestoreOutputPort
{
    /**
     * @inheritDoc
     */
    public function restore(TaskRestoreResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            [],
            'Successfully restored task',
            Response::HTTP_OK,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(TaskRestoreResponseModel $responseModel): ViewModel
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
    public function internalServerError(TaskRestoreResponseModel $responseModel, Throwable $exception): ViewModel
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
