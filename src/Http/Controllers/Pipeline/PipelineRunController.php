<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\Pipeline;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Run\PipelineRunInputPort;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Run\PipelineRunRequestModel;

/**
 * Class PipelineRunController
 * @package ConsulConfigManager\Tasks\Http\Controllers\Pipeline
 */
class PipelineRunController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelineRunInputPort
     */
    private PipelineRunInputPort $interactor;

    /**
     * PipelineRunController constructor.
     * @param PipelineRunInputPort $interactor
     * @return void
     */
    public function __construct(PipelineRunInputPort $interactor)
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
        $viewModel = $this->interactor->run(
            new PipelineRunRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
