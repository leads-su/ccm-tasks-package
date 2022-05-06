<?php

namespace ConsulConfigManager\Tasks\Presenters\Task;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Task\Delete\TaskDeleteOutputPort;
use ConsulConfigManager\Tasks\UseCases\Task\Delete\TaskDeleteResponseModel;

/**
 * Class TaskDeleteHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\Task
 */
class TaskDeleteHttpPresenter implements TaskDeleteOutputPort
{
    /**
     * @inheritDoc
     */
    public function delete(TaskDeleteResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            [],
            'Successfully deleted task',
            Response::HTTP_OK,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(TaskDeleteResponseModel $responseModel): ViewModel
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
    public function internalServerError(TaskDeleteResponseModel $responseModel, Throwable $exception): ViewModel
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
