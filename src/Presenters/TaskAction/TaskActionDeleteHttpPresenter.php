<?php

namespace ConsulConfigManager\Tasks\Presenters\TaskAction;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Delete\TaskActionDeleteOutputPort;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Delete\TaskActionDeleteResponseModel;

/**
 * Class TaskActionDeleteHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\TaskAction
 */
class TaskActionDeleteHttpPresenter implements TaskActionDeleteOutputPort
{
    /**
     * @inheritDoc
     */
    public function delete(TaskActionDeleteResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            [],
            'Successfully deleted task action',
            Response::HTTP_OK,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(TaskActionDeleteResponseModel $responseModel, string $model): ViewModel
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
    public function internalServerError(TaskActionDeleteResponseModel $responseModel, Throwable $exception): ViewModel
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
