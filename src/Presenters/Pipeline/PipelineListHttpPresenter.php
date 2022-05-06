<?php

namespace ConsulConfigManager\Tasks\Presenters\Pipeline;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Pipeline\List\PipelineListOutputPort;
use ConsulConfigManager\Tasks\UseCases\Pipeline\List\PipelineListResponseModel;

/**
 * Class PipelineListHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\Pipeline
 */
class PipelineListHttpPresenter implements PipelineListOutputPort
{
    /**
     * @inheritDoc
     */
    public function list(PipelineListResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntities(),
            'Successfully fetched list of pipelines',
            Response::HTTP_OK,
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(PipelineListResponseModel $responseModel, Throwable $exception): ViewModel
    {
        if (config('app.debug')) {
            throw $exception;
        }

        return new HttpResponseViewModel(response_error(
            $exception,
            'Unable to retrieve pipelines list',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreEnd
}
