<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\Pipeline;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Create\PipelineCreateInputPort;
use ConsulConfigManager\Tasks\Http\Requests\Pipeline\PipelineCreateUpdateRequest;
use ConsulConfigManager\Tasks\UseCases\Pipeline\Create\PipelineCreateRequestModel;

/**
 * Class PipelineCreateController
 * @package ConsulConfigManager\Tasks\Http\Contollers\Pipeline
 */
class PipelineCreateController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelineCreateInputPort
     */
    private PipelineCreateInputPort $interactor;

    /**
     * PipelineCreateController constructor.
     * @param PipelineCreateInputPort $interactor
     * @return void
     */
    public function __construct(PipelineCreateInputPort $interactor)
    {
        $this->interactor =$interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param PipelineCreateUpdateRequest $request
     * @return Response|null
     */
    public function __invoke(PipelineCreateUpdateRequest $request): ?Response
    {
        $viewModel = $this->interactor->create(
            new PipelineCreateRequestModel($request)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
