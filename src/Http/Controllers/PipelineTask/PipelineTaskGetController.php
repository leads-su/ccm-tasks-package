<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\PipelineTask;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Get\PipelineTaskGetInputPort;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Get\PipelineTaskGetRequestModel;

/**
 * Class PipelineTaskGetController
 * @package ConsulConfigManager\Tasks\Http\Controllers\PipelineTask
 */
class PipelineTaskGetController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelineTaskGetInputPort
     */
    private PipelineTaskGetInputPort $interactor;

    /**
     * PipelineTaskGetController constructor.
     * @param PipelineTaskGetInputPort $interactor
     * @return void
     */
    public function __construct(PipelineTaskGetInputPort $interactor)
    {
        $this->interactor = $interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param Request $request
     * @param string $pipelineIdentifier
     * @param string $taskIdentifier
     * @return Response|null
     */
    public function __invoke(Request $request, string $pipelineIdentifier, string $taskIdentifier): ?Response
    {
        $viewModel = $this->interactor->information(
            new PipelineTaskGetRequestModel($request, $pipelineIdentifier, $taskIdentifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
