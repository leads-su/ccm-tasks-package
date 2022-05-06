<?php

namespace ConsulConfigManager\Tasks\Presenters\TaskAction;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Restore\TaskActionRestoreOutputPort;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Restore\TaskActionRestoreResponseModel;

/**
 * Class TaskActionRestoreHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\TaskAction
 */
class TaskActionRestoreHttpPresenter implements TaskActionRestoreOutputPort
{
    /**
     * @inheritDoc
     */
    public function restore(TaskActionRestoreResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            [],
            'Successfully restored task action reference',
            Response::HTTP_OK,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(TaskActionRestoreResponseModel $responseModel, string $model): ViewModel
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
    public function internalServerError(TaskActionRestoreResponseModel $responseModel, Throwable $exception): ViewModel
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
