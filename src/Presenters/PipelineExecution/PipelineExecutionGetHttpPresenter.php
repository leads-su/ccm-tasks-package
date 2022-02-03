<?php

namespace ConsulConfigManager\Tasks\Presenters\PipelineExecution;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelineExecution\Get\PipelineExecutionGetOutputPort;
use ConsulConfigManager\Tasks\UseCases\PipelineExecution\Get\PipelineExecutionGetResponseModel;

/**
 * Class PipelineExecutionGetHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\PipelineExecution
 */
class PipelineExecutionGetHttpPresenter implements PipelineExecutionGetOutputPort {

    /**
     * @inheritDoc
     */
    public function get(PipelineExecutionGetResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntity(),
            'Successfully fetched pipeline execution information',
            Response::HTTP_OK
        ));
    }

    /**
     * @inheritDoc
     */
    public function notFound(PipelineExecutionGetResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_error(
            [],
            'Unable to find requested pipeline execution'
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(PipelineExecutionGetResponseModel $responseModel, Throwable $throwable): ViewModel
    {
        if (config('app.debug')) {
            throw $throwable;
        }

        return new HttpResponseViewModel(response_error(
            $throwable,
            'Unable to fetch pipeline execution information',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreEnd
}
