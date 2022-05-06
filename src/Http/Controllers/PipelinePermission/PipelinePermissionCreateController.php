<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\PipelinePermission;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\Create\PipelinePermissionCreateInputPort;
use ConsulConfigManager\Tasks\Http\Requests\PipelinePermission\PipelinePermissionCreateDeleteRequest;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\Create\PipelinePermissionCreateRequestModel;

/**
 * Class PipelinePermissionCreateController
 * @package ConsulConfigManager\Tasks\Http\Controllers\PipelinePermission
 */
class PipelinePermissionCreateController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelinePermissionCreateInputPort
     */
    private PipelinePermissionCreateInputPort $interactor;

    /**
     * PipelinePermissionCreateController constructor.
     * @param PipelinePermissionCreateInputPort $interactor
     * @return void
     */
    public function __construct(PipelinePermissionCreateInputPort $interactor)
    {
        $this->interactor = $interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param PipelinePermissionCreateDeleteRequest $request
     * @return Response|null
     */
    public function __invoke(PipelinePermissionCreateDeleteRequest $request): ?Response
    {
        $viewModel = $this->interactor->create(
            new PipelinePermissionCreateRequestModel($request)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
