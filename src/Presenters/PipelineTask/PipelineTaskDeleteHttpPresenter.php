<?php

namespace ConsulConfigManager\Tasks\Presenters\PipelineTask;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Delete\PipelineTaskDeleteOutputPort;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Delete\PipelineTaskDeleteResponseModel;

/**
 * Class PipelineTaskDeleteHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\PipelineTask
 */
class PipelineTaskDeleteHttpPresenter implements PipelineTaskDeleteOutputPort
{
    /**
     * @inheritDoc
     */
    public function delete(PipelineTaskDeleteResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            [],
            'Successfully deleted pipeline task',
            Response::HTTP_OK,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(PipelineTaskDeleteResponseModel $responseModel, string $model): ViewModel
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
    public function internalServerError(PipelineTaskDeleteResponseModel $responseModel, Throwable $exception): ViewModel
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
    // @codeCoverageIgnoreEnd
}
