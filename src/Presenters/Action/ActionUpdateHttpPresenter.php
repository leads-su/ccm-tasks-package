<?php

namespace ConsulConfigManager\Tasks\Presenters\Action;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Action\Update\ActionUpdateOutputPort;
use ConsulConfigManager\Tasks\UseCases\Action\Update\ActionUpdateResponseModel;

/**
 * Class ActionUpdateHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\Action
 */
class ActionUpdateHttpPresenter implements ActionUpdateOutputPort
{
    /**
     * @inheritDoc
     */
    public function update(ActionUpdateResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntity()->toArray(),
            'Successfully updated action',
            Response::HTTP_CREATED,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(ActionUpdateResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_error(
            [],
            'Unable to find action with specified identifier'
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(ActionUpdateResponseModel $responseModel, Throwable $exception): ViewModel
    {
        if (config('app.debug')) {
            throw $exception;
        }

        return new HttpResponseViewModel(response_error(
            $exception,
            'Unable to update action',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreStart
}
