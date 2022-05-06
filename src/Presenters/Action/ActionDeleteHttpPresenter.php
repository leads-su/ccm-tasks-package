<?php

namespace ConsulConfigManager\Tasks\Presenters\Action;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Action\Delete\ActionDeleteOutputPort;
use ConsulConfigManager\Tasks\UseCases\Action\Delete\ActionDeleteResponseModel;

/**
 * Class ActionDeleteHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\Action
 */
class ActionDeleteHttpPresenter implements ActionDeleteOutputPort
{
    /**
     * @inheritDoc
     */
    public function delete(ActionDeleteResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            [],
            'Successfully deleted action',
            Response::HTTP_OK,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(ActionDeleteResponseModel $responseModel): ViewModel
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
    public function internalServerError(ActionDeleteResponseModel $responseModel, Throwable $exception): ViewModel
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
