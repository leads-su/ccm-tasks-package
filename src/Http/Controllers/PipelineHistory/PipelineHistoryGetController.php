<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\PipelineHistory;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelineHistory\Get\PipelineHistoryGetInputPort;
use ConsulConfigManager\Tasks\UseCases\PipelineHistory\Get\PipelineHistoryGetRequestModel;

/**
 * Class PipelineHistoryGetController
 * @package ConsulConfigManager\Tasks\Http\Controllers\PipelineHistory
 */
class PipelineHistoryGetController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelineHistoryGetInputPort
     */
    private PipelineHistoryGetInputPort $interactor;

    /**
     * PipelineHistoryGetController constructor.
     * @param PipelineHistoryGetInputPort $interactor
     * @return void
     */
    public function __construct(PipelineHistoryGetInputPort $interactor)
    {
        $this->interactor = $interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param Request $request
     * @param string $identifier
     * @return Response|null
     */
    public function __invoke(Request $request, string $identifier): ?Response
    {
        $viewModel = $this->interactor->get(
            new PipelineHistoryGetRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
