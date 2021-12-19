<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\PipelineTask;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Delete\PipelineTaskDeleteInputPort;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Delete\PipelineTaskDeleteRequestModel;

/**
 * Class PipelineTaskDeleteController
 * @package ConsulConfigManager\Tasks\Http\Controllers\PipelineTask
 */
class PipelineTaskDeleteController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelineTaskDeleteInputPort
     */
    private PipelineTaskDeleteInputPort $interactor;

    /**
     * PipelineTaskDeleteController constructor.
     * @param PipelineTaskDeleteInputPort $interactor
     * @return void
     */
    public function __construct(PipelineTaskDeleteInputPort $interactor)
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
        $viewModel = $this->interactor->delete(
            new PipelineTaskDeleteRequestModel($request, $pipelineIdentifier, $taskIdentifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
