<?php

namespace ConsulConfigManager\Tasks\Presenters\PipelineHistory;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelineHistory\Get\PipelineHistoryGetOutputPort;
use ConsulConfigManager\Tasks\UseCases\PipelineHistory\Get\PipelineHistoryGetResponseModel;

/**
 * Class PipelineHistoryGetHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\PipelineHistory
 */
class PipelineHistoryGetHttpPresenter implements PipelineHistoryGetOutputPort
{
    /**
     * @inheritDoc
     */
    public function get(PipelineHistoryGetResponseModel $responseModel): ViewModel
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
    public function notFound(PipelineHistoryGetResponseModel $responseModel): ViewModel
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
    public function internalServerError(PipelineHistoryGetResponseModel $responseModel, Throwable $throwable): ViewModel
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
