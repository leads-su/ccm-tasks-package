<?php

namespace ConsulConfigManager\Tasks\Presenters\PipelineExecution;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelineExecution\List\PipelineExecutionListOutputPort;
use ConsulConfigManager\Tasks\UseCases\PipelineExecution\List\PipelineExecutionListResponseModel;

/**
 * Class PipelineExecutionListHttpPresenter
 * @package ConsulConfigManager\Tasks\Presenters\PipelineExecution
 */
class PipelineExecutionListHttpPresenter implements PipelineExecutionListOutputPort {

    /**
     * @inheritDoc
     */
    public function list(PipelineExecutionListResponseModel $responseModel): ViewModel
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
    public function internalServerError(PipelineExecutionListResponseModel $responseModel, Throwable $throwable): ViewModel
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
