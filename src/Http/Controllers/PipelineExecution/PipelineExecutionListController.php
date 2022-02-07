<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\PipelineExecution;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelineExecution\List\PipelineExecutionListInputPort;
use ConsulConfigManager\Tasks\UseCases\PipelineExecution\List\PipelineExecutionListRequestModel;

/**
 * Class PipelineExecutionListController
 * @package ConsulConfigManager\Tasks\Http\Controllers\PipelineExecution
 */
class PipelineExecutionListController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelineExecutionListInputPort
     */
    private PipelineExecutionListInputPort $interactor;

    /**
     * PipelineExecutionListController constructor.
     * @param PipelineExecutionListInputPort $interactor
     * @return void
     */
    public function __construct(PipelineExecutionListInputPort $interactor)
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
            new PipelineExecutionListRequestModel($request)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
