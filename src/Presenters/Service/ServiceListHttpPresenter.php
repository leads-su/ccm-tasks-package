<?php

namespace ConsulConfigManager\Tasks\Presenters\Service;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Service\List\ServiceListOutputPort;
use ConsulConfigManager\Tasks\UseCases\Service\List\ServiceListResponseModel;

/**
 * Class ServiceListHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\Service
 */
class ServiceListHttpPresenter implements ServiceListOutputPort
{
    /**
     * @inheritDoc
     */
    public function list(ServiceListResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntities()->toArray(),
            'Successfully fetched list of services',
            Response::HTTP_OK,
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(ServiceListResponseModel $responseModel, Throwable $exception): ViewModel
    {
        if (config('app.debug')) {
            throw $exception;
        }

        return new HttpResponseViewModel(response_error(
            $exception,
            'Unable to fetch list of services',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreStart
}
