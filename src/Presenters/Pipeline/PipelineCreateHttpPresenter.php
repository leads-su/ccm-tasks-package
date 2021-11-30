<?php

namespace ConsulConfigManager\Tasks\Presenters\Pipeline;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Create\PipelineCreateOutputPort;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Create\PipelineCreateResponseModel;

/**
 * Class PipelineCreateHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\Pipeline
 */
class PipelineCreateHttpPresenter implements PipelineCreateOutputPort
{
    /**
     * @inheritDoc
     */
    public function create(PipelineCreateResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntity()->toArray(),
            'Successfully created new pipeline',
            Response::HTTP_CREATED,
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(PipelineCreateResponseModel $responseModel, Throwable $exception): ViewModel
    {
        if (config('app.debug')) {
            throw $exception;
        }

        return new HttpResponseViewModel(response_error(
            $exception,
            'Unable to create new pipeline',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreStart
}
