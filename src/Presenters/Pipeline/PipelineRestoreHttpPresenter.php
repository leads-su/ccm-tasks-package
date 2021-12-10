<?php

namespace ConsulConfigManager\Tasks\Presenters\Pipeline;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Restore\PipelineRestoreOutputPort;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Restore\PipelineRestoreResponseModel;

/**
 * Class PipelineRestoreHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\Pipeline
 */
class PipelineRestoreHttpPresenter implements PipelineRestoreOutputPort
{
    /**
     * @inheritDoc
     */
    public function restore(PipelineRestoreResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            [],
            'Successfully restored pipeline',
            Response::HTTP_OK,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(PipelineRestoreResponseModel $responseModel): ViewModel
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
    public function internalServerError(PipelineRestoreResponseModel $responseModel, Throwable $exception): ViewModel
    {
        if (config('app.debug')) {
            throw $exception;
        }

        return new HttpResponseViewModel(response_error(
            $exception,
            'Unable to retrieve pipelines information',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreEnd
}
