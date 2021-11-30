<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\Pipeline;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Update\PipelineUpdateInputPort;
use ConsulConfigManager\Tasks\Http\Requests\Pipeline\PipelineCreateUpdateRequest;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Update\PipelineUpdateRequestModel;

/**
 * Class PipelineUpdateController
 * @package ConsulConfigManager\Tasks\Http\Contollers\Pipeline
 */
class PipelineUpdateController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelineUpdateInputPort
     */
    private PipelineUpdateInputPort $interactor;

    /**
     * PipelineUpdateController constructor.
     * @param PipelineUpdateInputPort $interactor
     * @return void
     */
    public function __construct(PipelineUpdateInputPort $interactor)
    {
        $this->interactor =$interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param PipelineCreateUpdateRequest $request
     * @param string $identifier
     * @return Response|null
     */
    public function __invoke(PipelineCreateUpdateRequest $request, string $identifier): ?Response
    {
        $viewModel = $this->interactor->update(
            new PipelineUpdateRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
