<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\Pipeline;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Pipeline\List\PipelineListInputPort;
use ConsulConfigManager\Tasks\UseCases\Pipeline\List\PipelineListRequestModel;

/**
 * Class PipelineListController
 * @package ConsulConfigManager\Tasks\Http\Controllers\Pipeline
 */
class PipelineListController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelineListInputPort
     */
    private PipelineListInputPort $interactor;

    /**
     * PipelineListController constructor.
     * @param PipelineListInputPort $interactor
     * @return void
     */
    public function __construct(PipelineListInputPort $interactor)
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
            new PipelineListRequestModel($request)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
