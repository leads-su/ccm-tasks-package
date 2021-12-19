<?php

namespace ConsulConfigManager\Tasks\Presenters\TaskAction;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\TaskAction\List\TaskActionListOutputPort;
use ConsulConfigManager\Tasks\UseCases\TaskAction\List\TaskActionListResponseModel;

/**
 * Class TaskActionListHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\TaskAction
 */
class TaskActionListHttpPresenter implements TaskActionListOutputPort
{
    /**
     * @inheritDoc
     */
    public function list(TaskActionListResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntities()->toArray(),
            'Successfully fetched list of actions for requested task',
            Response::HTTP_OK,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(TaskActionListResponseModel $responseModel): ViewModel
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
    public function internalServerError(TaskActionListResponseModel $responseModel, Throwable $exception): ViewModel
    {
        if (config('app.debug')) {
            throw $exception;
        }

        return new HttpResponseViewModel(response_error(
            $exception,
            'Unable to perform restoration',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreEnd
}
