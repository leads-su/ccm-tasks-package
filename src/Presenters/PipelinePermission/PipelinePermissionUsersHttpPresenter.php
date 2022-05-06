<?php

namespace ConsulConfigManager\Tasks\Presenters\PipelinePermission;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\Users\PipelinePermissionUsersOutputPort;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\Users\PipelinePermissionUsersResponseModel;

/**
 * Class PipelinePermissionUsersHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\PipelinePermission
 */
class PipelinePermissionUsersHttpPresenter implements PipelinePermissionUsersOutputPort
{
    /**
     * @inheritDoc
     */
    public function users(PipelinePermissionUsersResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntities(),
            'Successfully fetched list of users with pipeline permissions',
            Response::HTTP_OK,
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(PipelinePermissionUsersResponseModel $responseModel, Throwable $throwable): ViewModel
    {
        if (config('app.debug')) {
            throw $throwable;
        }

        return new HttpResponseViewModel(response_error(
            $throwable,
            'Failed to fetch list of users with pipeline permissions',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreEnd
}
