<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\PipelineExecution;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelineExecution\Get\PipelineExecutionGetInputPort;
use ConsulConfigManager\Tasks\UseCases\PipelineExecution\Get\PipelineExecutionGetRequestModel;

/**
 * Class PipelineExecutionGetController
 * @package ConsulConfigManager\Tasks\Http\Controllers\PipelineExecution
 */
class PipelineExecutionGetController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelineExecutionGetInputPort
     */
    private PipelineExecutionGetInputPort $interactor;

    /**
     * PipelineExecutionGetController constructor.
     * @param PipelineExecutionGetInputPort $interactor
     * @return void
     */
    public function __construct(PipelineExecutionGetInputPort $interactor)
    {
        $this->interactor = $interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param Request $request
     * @param string $identifier
     * @param int $execution
     * @return Response|null
     */
    public function __invoke(Request $request, string $identifier, int $execution): ?Response
    {
        $viewModel = $this->interactor->get(
            new PipelineExecutionGetRequestModel($request, $identifier, $execution)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
