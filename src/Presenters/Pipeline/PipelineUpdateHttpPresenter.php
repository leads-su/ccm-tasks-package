<?php

namespace ConsulConfigManager\Tasks\Presenters\Pipeline;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Update\PipelineUpdateOutputPort;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Update\PipelineUpdateResponseModel;

/**
 * Class PipelineUpdateHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\Pipeline
 */
class PipelineUpdateHttpPresenter implements PipelineUpdateOutputPort
{
    /**
     * @inheritDoc
     */
    public function update(PipelineUpdateResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntity()->toArray(),
            'Successfully updated pipeline',
            Response::HTTP_CREATED,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(PipelineUpdateResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_error(
            [],
            'Unable to find pipeline with specified identifier'
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(PipelineUpdateResponseModel $responseModel, Throwable $exception): ViewModel
    {
        if (config('app.debug')) {
            throw $exception;
        }

        return new HttpResponseViewModel(response_error(
            $exception,
            'Unable to update pipeline',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreStart
}
