<?php

namespace ConsulConfigManager\Tasks\Presenters\PipelinePermission;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\List\PipelinePermissionListOutputPort;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\List\PipelinePermissionListResponseModel;

/**
 * Class PipelinePermissionListHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\PipelinePermission
 */
class PipelinePermissionListHttpPresenter implements PipelinePermissionListOutputPort
{
    /**
     * @inheritDoc
     */
    public function list(PipelinePermissionListResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntities(),
            'Successfully fetched list of user pipeline permissions',
            Response::HTTP_OK,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(PipelinePermissionListResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_error(
            $responseModel->getEntities(),
            'Unable to find user with specified identifier'
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(PipelinePermissionListResponseModel $responseModel, Throwable $throwable): ViewModel
    {
        if (config('app.debug')) {
            throw $throwable;
        }

        return new HttpResponseViewModel(response_error(
            $throwable,
            'Failed to fetch list of pipeline permissions for user',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreEnd
}
