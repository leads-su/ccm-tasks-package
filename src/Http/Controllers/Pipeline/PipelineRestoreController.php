<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\Pipeline;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Restore\PipelineRestoreInputPort;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Restore\PipelineRestoreRequestModel;

/**
 * Class PipelineRestoreController
 * @package ConsulConfigManager\Tasks\Http\Controllers\Pipeline
 */
class PipelineRestoreController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelineRestoreInputPort
     */
    private PipelineRestoreInputPort $interactor;

    /**
     * PipelineRestoreController constructor.
     * @param PipelineRestoreInputPort $interactor
     * @return void
     */
    public function __construct(PipelineRestoreInputPort $interactor)
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
        $viewModel = $this->interactor->restore(
            new PipelineRestoreRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
