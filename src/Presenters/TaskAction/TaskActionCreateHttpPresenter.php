<?php

namespace ConsulConfigManager\Tasks\Presenters\TaskAction;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Create\TaskActionCreateOutputPort;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Create\TaskActionCreateResponseModel;

/**
 * Class TaskActionCreateHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\TaskAction
 */
class TaskActionCreateHttpPresenter implements TaskActionCreateOutputPort
{
    /**
     * @inheritDoc
     */
    public function create(TaskActionCreateResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntity()->toArray(),
            'Successfully created new task action',
            Response::HTTP_CREATED,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(TaskActionCreateResponseModel $responseModel, string $model): ViewModel
    {
        return new HttpResponseViewModel(response_error(
            [],
            'Unable to find requested ' . strtolower(str_replace('ConsulConfigManager\\Tasks\\Models\\', '', $model))
        ));
    }

    /**
     * @inheritDoc
     */
    public function alreadyExists(TaskActionCreateResponseModel $responseModel, string $model): ViewModel
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
    public function internalServerError(TaskActionCreateResponseModel $responseModel, Throwable $exception): ViewModel
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
