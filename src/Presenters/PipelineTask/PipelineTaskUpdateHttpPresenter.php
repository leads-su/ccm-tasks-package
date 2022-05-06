<?php

namespace ConsulConfigManager\Tasks\Presenters\PipelineTask;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Update\PipelineTaskUpdateOutputPort;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Update\PipelineTaskUpdateResponseModel;

/**
 * Class PipelineTaskUpdateHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\PipelineTask
 */
class PipelineTaskUpdateHttpPresenter implements PipelineTaskUpdateOutputPort
{
    /**
     * @inheritDoc
     */
    public function update(PipelineTaskUpdateResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            [],
            'Successfully updated pipeline task',
            Response::HTTP_OK,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(PipelineTaskUpdateResponseModel $responseModel, string $model): ViewModel
    {
        return new HttpResponseViewModel(response_error(
            [],
            'Unable to find requested ' . strtolower(str_replace('ConsulConfigManager\\Tasks\\Models\\', '', $model))
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(PipelineTaskUpdateResponseModel $responseModel, Throwable $exception): ViewModel
    {
        if (config('app.debug')) {
            throw $exception;
        }

        return new HttpResponseViewModel(response_error(
            $exception,
            'Unable to perform update',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreStart
}
