<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\PipelineTask;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Update\PipelineTaskUpdateInputPort;
use ConsulConfigManager\Tasks\Http\Requests\PipelineTask\PipelineTaskCreateUpdateRequest;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Update\PipelineTaskUpdateRequestModel;

/**
 * Class PipelineTaskUpdateController
 * @package ConsulConfigManager\Tasks\Http\Contollers\PipelineTask
 */
class PipelineTaskUpdateController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelineTaskUpdateInputPort
     */
    private PipelineTaskUpdateInputPort $interactor;

    /**
     * PipelineTaskUpdateController constructor.
     * @param PipelineTaskUpdateInputPort $interactor
     * @return void
     */
    public function __construct(PipelineTaskUpdateInputPort $interactor)
    {
        $this->interactor =$interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param PipelineTaskCreateUpdateRequest $request
     * @param string $pipelineIdentifier
     * @param string $taskIdentifier
     * @return Response|null
     */
    public function __invoke(PipelineTaskCreateUpdateRequest $request, string $pipelineIdentifier, string $taskIdentifier): ?Response
    {
        $viewModel = $this->interactor->update(
            new PipelineTaskUpdateRequestModel($request, $pipelineIdentifier, $taskIdentifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
