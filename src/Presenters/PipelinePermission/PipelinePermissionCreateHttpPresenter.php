<?php

namespace ConsulConfigManager\Tasks\Presenters\PipelinePermission;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\Create\PipelinePermissionCreateOutputPort;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\Create\PipelinePermissionCreateResponseModel;

/**
 * Class PipelinePermissionCreateHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\PipelinePermission
 */
class PipelinePermissionCreateHttpPresenter implements PipelinePermissionCreateOutputPort
{
    /**
     * @inheritDoc
     */
    public function create(PipelinePermissionCreateResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntity(),
            'Successfully created pipeline permission',
            Response::HTTP_OK,
        ));
    }

    /**
     * @inheritDoc
     */
    public function alreadyExists(PipelinePermissionCreateResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_error(
            $responseModel->getEntity(),
            'Specified pipeline permission already exists',
            Response::HTTP_CONFLICT
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(PipelinePermissionCreateResponseModel $responseModel, Throwable $throwable): ViewModel
    {
        if (config('app.debug')) {
            throw $throwable;
        }

        return new HttpResponseViewModel(response_error(
            $throwable,
            'Failed to create pipeline permission for user',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreEnd
}
