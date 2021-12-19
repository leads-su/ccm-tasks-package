<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\PipelineTask;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Restore\PipelineTaskRestoreInputPort;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Restore\PipelineTaskRestoreRequestModel;

/**
 * Class PipelineTaskRestoreController
 * @package ConsulConfigManager\Tasks\Http\Controllers\PipelineTask
 */
class PipelineTaskRestoreController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelineTaskRestoreInputPort
     */
    private PipelineTaskRestoreInputPort $interactor;

    /**
     * PipelineTaskRestoreController constructor.
     * @param PipelineTaskRestoreInputPort $interactor
     * @return void
     */
    public function __construct(PipelineTaskRestoreInputPort $interactor)
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
        $viewModel = $this->interactor->restore(
            new PipelineTaskRestoreRequestModel($request, $pipelineIdentifier, $taskIdentifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
