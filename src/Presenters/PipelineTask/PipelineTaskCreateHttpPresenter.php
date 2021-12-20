<?php

namespace ConsulConfigManager\Tasks\Presenters\PipelineTask;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Create\PipelineTaskCreateOutputPort;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Create\PipelineTaskCreateResponseModel;

/**
 * Class PipelineTaskCreateHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\PipelineTask
 */
class PipelineTaskCreateHttpPresenter implements PipelineTaskCreateOutputPort
{
    /**
     * @inheritDoc
     */
    public function create(PipelineTaskCreateResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntity()->toArray(),
            'Successfully created new pipeline task',
            Response::HTTP_CREATED,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(PipelineTaskCreateResponseModel $responseModel, string $model): ViewModel
    {
        return new HttpResponseViewModel(response_error(
            [],
            'Unable to find requested ' . strtolower(str_replace('ConsulConfigManager\\Tasks\\Models\\', '', $model))
        ));
    }

    /**
     * @inheritDoc
     */
    public function alreadyExists(PipelineTaskCreateResponseModel $responseModel, string $model): ViewModel
    {
        return new HttpResponseViewModel(response_error(
            [],
            'Model already exists: ' . strtolower(str_replace('ConsulConfigManager\\Tasks\\Models\\', '', $model)),
            Response::HTTP_CONFLICT,
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(PipelineTaskCreateResponseModel $responseModel, Throwable $exception): ViewModel
    {
        if (config('app.debug')) {
            throw $exception;
        }

        return new HttpResponseViewModel(response_error(
            $exception,
            'Unable to perform restoration',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreStart
}
