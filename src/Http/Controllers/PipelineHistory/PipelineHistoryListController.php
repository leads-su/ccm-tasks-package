<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\PipelineHistory;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelineHistory\List\PipelineHistoryListInputPort;
use ConsulConfigManager\Tasks\UseCases\PipelineHistory\List\PipelineHistoryListRequestModel;

/**
 * Class PipelineHistoryListController
 * @package ConsulConfigManager\Tasks\Http\Controllers\PipelineHistory
 */
class PipelineHistoryListController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelineHistoryListInputPort
     */
    private PipelineHistoryListInputPort $interactor;

    /**
     * PipelineHistoryListController constructor.
     * @param PipelineHistoryListInputPort $interactor
     * @return void
     */
    public function __construct(PipelineHistoryListInputPort $interactor)
    {
        $this->interactor = $interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param Request $request
     * @return Response|null
     */
    public function __invoke(Request $request): ?Response
    {
        $viewModel = $this->interactor->list(
            new PipelineHistoryListRequestModel($request)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
