<?php

namespace ConsulConfigManager\Tasks\Presenters\PipelinePermission;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\My\PipelinePermissionMyOutputPort;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\My\PipelinePermissionMyResponseModel;

/**
 * Class PipelinePermissionMyHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\PipelinePermission
 */
class PipelinePermissionMyHttpPresenter implements PipelinePermissionMyOutputPort
{
    /**
     * @inheritDoc
     */
    public function my(PipelinePermissionMyResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntities(),
            'Successfully fetched list of my pipeline permissions',
            Response::HTTP_OK,
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(PipelinePermissionMyResponseModel $responseModel, Throwable $throwable): ViewModel
    {
        if (config('app.debug')) {
            throw $throwable;
        }

        return new HttpResponseViewModel(response_error(
            $throwable,
            'Failed to fetch list of my pipeline permissions',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreEnd
}
