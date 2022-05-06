<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\Pipeline;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Delete\PipelineDeleteInputPort;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Delete\PipelineDeleteRequestModel;

/**
 * Class PipelineDeleteController
 * @package ConsulConfigManager\Tasks\Http\Controllers\Pipeline
 */
class PipelineDeleteController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelineDeleteInputPort
     */
    private PipelineDeleteInputPort $interactor;

    /**
     * PipelineDeleteController constructor.
     * @param PipelineDeleteInputPort $interactor
     * @return void
     */
    public function __construct(PipelineDeleteInputPort $interactor)
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
        $viewModel = $this->interactor->delete(
            new PipelineDeleteRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
