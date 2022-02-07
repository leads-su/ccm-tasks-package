<?php

namespace ConsulConfigManager\Tasks\Presenters\ActionExecution;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\ActionExecution\List\ActionExecutionListOutputPort;
use ConsulConfigManager\Tasks\UseCases\ActionExecution\List\ActionExecutionListResponseModel;

/**
 * Class ActionExecutionListHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\ActionExecution
 */
class ActionExecutionListHttpPresenter implements ActionExecutionListOutputPort
{
    /**
     * @inheritDoc
     */
    public function list(ActionExecutionListResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntities()->toArray(),
            'Successfully fetched list of action executions',
            Response::HTTP_OK
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(ActionExecutionListResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_error(
            [],
            'Unable to find requested action'
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(ActionExecutionListResponseModel $responseModel, Throwable $throwable): ViewModel
    {
        if (config('app.debug')) {
            throw $throwable;
        }

        return new HttpResponseViewModel(response_error(
            $throwable,
            'Unable to fetch list of action executions',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreEnd
}
