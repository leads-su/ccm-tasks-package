<?php

namespace ConsulConfigManager\Tasks\Presenters\ActionExecution;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\ActionExecution\Get\ActionExecutionGetOutputPort;
use ConsulConfigManager\Tasks\UseCases\ActionExecution\Get\ActionExecutionGetResponseModel;

/**
 * Class ActionExecutionGetHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\ActionExecution
 */
class ActionExecutionGetHttpPresenter implements ActionExecutionGetOutputPort {

    /**
     * @inheritDoc
     */
    public function get(ActionExecutionGetResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntity(),
            'Successfully fetched action execution information',
            Response::HTTP_OK
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(ActionExecutionGetResponseModel $responseModel): ViewModel
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
    public function internalServerError(ActionExecutionGetResponseModel $responseModel, Throwable $throwable): ViewModel
    {
        if (config('app.debug')) {
            throw $throwable;
        }

        return new HttpResponseViewModel(response_error(
            $throwable,
            'Unable to fetch action execution information',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreEnd
}
