<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\Pipeline;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Get\PipelineGetInputPort;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Get\PipelineGetRequestModel;

/**
 * Class PipelineGetController
 * @package ConsulConfigManager\Tasks\Http\Controllers\Pipeline
 */
class PipelineGetController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelineGetInputPort
     */
    private PipelineGetInputPort $interactor;

    /**
     * PipelineGetController constructor.
     * @param PipelineGetInputPort $interactor
     * @return void
     */
    public function __construct(PipelineGetInputPort $interactor)
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
            new PipelineGetRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
