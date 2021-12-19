<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\PipelineTask;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\List\PipelineTaskListInputPort;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\List\PipelineTaskListRequestModel;

/**
 * Class PipelineTaskListController
 * @package ConsulConfigManager\Tasks\Http\Controllers\PipelineTask
 */
class PipelineTaskListController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelineTaskListInputPort
     */
    private PipelineTaskListInputPort $interactor;

    /**
     * PipelineTaskListController constructor.
     * @param PipelineTaskListInputPort $interactor
     * @return void
     */
    public function __construct(PipelineTaskListInputPort $interactor)
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
        $viewModel = $this->interactor->list(
            new PipelineTaskListRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
