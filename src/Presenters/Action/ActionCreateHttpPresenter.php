<?php

namespace ConsulConfigManager\Tasks\Presenters\Action;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Action\Create\ActionCreateOutputPort;
use ConsulConfigManager\Tasks\UseCases\Action\Create\ActionCreateResponseModel;

/**
 * Class ActionCreateHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\Action
 */
class ActionCreateHttpPresenter implements ActionCreateOutputPort
{
    /**
     * @inheritDoc
     */
    public function create(ActionCreateResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntity()->toArray(),
            'Successfully created new action',
            Response::HTTP_CREATED,
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(ActionCreateResponseModel $responseModel, Throwable $exception): ViewModel
    {
        if (config('app.debug')) {
            throw $exception;
        }

        return new HttpResponseViewModel(response_error(
            $exception,
            'Unable to create new action',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreStart
}
