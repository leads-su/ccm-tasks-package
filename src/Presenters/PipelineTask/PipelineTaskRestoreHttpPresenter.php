<?php

namespace ConsulConfigManager\Tasks\Presenters\PipelineTask;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Restore\PipelineTaskRestoreOutputPort;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Restore\PipelineTaskRestoreResponseModel;

/**
 * Class PipelineTaskRestoreHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\PipelineTask
 */
class PipelineTaskRestoreHttpPresenter implements PipelineTaskRestoreOutputPort
{
    /**
     * @inheritDoc
     */
    public function restore(PipelineTaskRestoreResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            [],
            'Successfully restored pipeline task reference',
            Response::HTTP_OK,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(PipelineTaskRestoreResponseModel $responseModel, string $model): ViewModel
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
    public function internalServerError(PipelineTaskRestoreResponseModel $responseModel, Throwable $exception): ViewModel
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
