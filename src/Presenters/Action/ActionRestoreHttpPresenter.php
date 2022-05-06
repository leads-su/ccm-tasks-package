<?php

namespace ConsulConfigManager\Tasks\Presenters\Action;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Action\Restore\ActionRestoreOutputPort;
use ConsulConfigManager\Tasks\UseCases\Action\Restore\ActionRestoreResponseModel;

/**
 * Class ActionRestoreHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\Action
 */
class ActionRestoreHttpPresenter implements ActionRestoreOutputPort
{
    /**
     * @inheritDoc
     */
    public function restore(ActionRestoreResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            [],
            'Successfully restored action',
            Response::HTTP_OK,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(ActionRestoreResponseModel $responseModel): ViewModel
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
    public function internalServerError(ActionRestoreResponseModel $responseModel, Throwable $exception): ViewModel
    {
        if (config('app.debug')) {
            throw $exception;
        }

        return new HttpResponseViewModel(response_error(
            $exception,
            'Unable to retrieve actions information',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreEnd
}
