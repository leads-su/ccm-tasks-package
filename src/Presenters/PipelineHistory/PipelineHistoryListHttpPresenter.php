<?php

namespace ConsulConfigManager\Tasks\Presenters\PipelineHistory;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelineHistory\List\PipelineHistoryListOutputPort;
use ConsulConfigManager\Tasks\UseCases\PipelineHistory\List\PipelineHistoryListResponseModel;

/**
 * Class PipelineHistoryListHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\PipelineHistory
 */
class PipelineHistoryListHttpPresenter implements PipelineHistoryListOutputPort
{
    /**
     * @inheritDoc
     */
    public function list(PipelineHistoryListResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(response_success(
            $responseModel->getEntities()->toArray(),
            'Successfully fetched list of pipeline executions',
            Response::HTTP_OK
        ));
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function internalServerError(PipelineHistoryListResponseModel $responseModel, Throwable $throwable): ViewModel
    {
        if (config('app.debug')) {
            throw $throwable;
        }

        return new HttpResponseViewModel(response_error(
            $throwable,
            'Unable to fetch list of pipeline executions',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        ));
    }
    // @codeCoverageIgnoreEnd
}
