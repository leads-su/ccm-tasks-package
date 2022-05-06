<?php

namespace ConsulConfigManager\Tasks\Presenters\PipelineTask;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\List\PipelineTaskListOutputPort;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\List\PipelineTaskListResponseModel;

/**
 * Class PipelineTaskListHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\PipelineTask
 */
class PipelineTaskListHttpPresenter implements PipelineTaskListOutputPort
{
    /**
     * @inheritDoc
     */
    public function list(PipelineTaskListResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntities()->toArray(),
            'Successfully fetched list of tasks for requested pipeline',
            Response::HTTP_OK,
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(PipelineTaskListResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_error(
            [],
            'Unable to find requested pipeline'
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(PipelineTaskListResponseModel $responseModel, Throwable $exception): ViewModel
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
