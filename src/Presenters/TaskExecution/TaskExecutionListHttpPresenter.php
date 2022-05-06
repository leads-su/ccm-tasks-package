<?php

namespace ConsulConfigManager\Tasks\Presenters\TaskExecution;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\TaskExecution\List\TaskExecutionListOutputPort;
use ConsulConfigManager\Tasks\UseCases\TaskExecution\List\TaskExecutionListResponseModel;

/**
 * Class TaskExecutionListHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\TaskExecution
 */
class TaskExecutionListHttpPresenter implements TaskExecutionListOutputPort
{
    /**
     * @inheritDoc
     */
    public function list(TaskExecutionListResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntities()->toArray(),
            'Successfully fetched list of task executions',
            Response::HTTP_OK
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(TaskExecutionListResponseModel $responseModel): ViewModel
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
    public function internalServerError(TaskExecutionListResponseModel $responseModel, Throwable $throwable): ViewModel
    {
        if (config('app.debug')) {
            throw $throwable;
        }

        return new HttpResponseViewModel(response_error(
            $throwable,
            'Unable to fetch list of task executions',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreEnd
}
