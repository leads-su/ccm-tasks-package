<?php

namespace ConsulConfigManager\Tasks\Presenters\Pipeline;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Run\PipelineRunOutputPort;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Run\PipelineRunResponseModel;

/**
 * Class PipelineRunHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\Pipeline
 */
class PipelineRunHttpPresenter implements PipelineRunOutputPort
{
    /**
     * @inheritDoc
     */
    public function run(PipelineRunResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            [],
            'Successfully started pipeline',
            Response::HTTP_OK,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(PipelineRunResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_error(
            [],
            'Unable to find requested pipeline'
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(PipelineRunResponseModel $responseModel, Throwable $exception): ViewModel
    {
        if (config('app.debug')) {
            throw $exception;
        }

        return new HttpResponseViewModel(response_error(
            $exception,
            'Unable to start pipelines',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreEnd
}
