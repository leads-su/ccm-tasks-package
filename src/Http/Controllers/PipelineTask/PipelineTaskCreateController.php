<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\PipelineTask;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Create\PipelineTaskCreateInputPort;
use ConsulConfigManager\Tasks\Http\Requests\PipelineTask\PipelineTaskCreateUpdateRequest;
use ConsulConfigManager\Tasks\UseCases\PipelineTask\Create\PipelineTaskCreateRequestModel;

/**
 * Class PipelineTaskCreateController
 * @package ConsulConfigManager\Tasks\Http\Contollers\PipelineTask
 */
class PipelineTaskCreateController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelineTaskCreateInputPort
     */
    private PipelineTaskCreateInputPort $interactor;

    /**
     * PipelineTaskCreateController constructor.
     * @param PipelineTaskCreateInputPort $interactor
     * @return void
     */
    public function __construct(PipelineTaskCreateInputPort $interactor)
    {
        $this->interactor =$interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param PipelineTaskCreateUpdateRequest $request
     * @param string $identifier
     * @return Response|null
     */
    public function __invoke(PipelineTaskCreateUpdateRequest $request, string $identifier): ?Response
    {
        $viewModel = $this->interactor->create(
            new PipelineTaskCreateRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
