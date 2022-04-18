<?php

namespace ConsulConfigManager\Tasks\Presenters\PipelinePermission;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\Delete\PipelinePermissionDeleteOutputPort;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\Delete\PipelinePermissionDeleteResponseModel;

/**
 * Class PipelinePermissionDeleteHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\PipelinePermission
 */
class PipelinePermissionDeleteHttpPresenter implements PipelinePermissionDeleteOutputPort
{
    /**
     * @inheritDoc
     */
    public function delete(PipelinePermissionDeleteResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntity(),
            'Successfully deleted pipeline permission',
            Response::HTTP_OK,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(PipelinePermissionDeleteResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_error(
            $responseModel->getEntity(),
            'Specified pipeline permission could not be found'
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(PipelinePermissionDeleteResponseModel $responseModel, Throwable $throwable): ViewModel
    {
        if (config('app.debug')) {
            throw $throwable;
        }

        return new HttpResponseViewModel(response_error(
            $throwable,
            'Failed to delete pipeline permission from user',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreEnd
}
