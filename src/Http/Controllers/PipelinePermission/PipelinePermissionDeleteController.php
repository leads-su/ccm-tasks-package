<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\PipelinePermission;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\Delete\PipelinePermissionDeleteInputPort;
use ConsulConfigManager\Tasks\Http\Requests\PipelinePermission\PipelinePermissionCreateDeleteRequest;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\Delete\PipelinePermissionDeleteRequestModel;

/**
 * Class PipelinePermissionDeleteController
 * @package ConsulConfigManager\Tasks\Http\Controllers\PipelinePermission
 */
class PipelinePermissionDeleteController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelinePermissionDeleteInputPort
     */
    private PipelinePermissionDeleteInputPort $interactor;

    /**
     * PipelinePermissionDeleteController constructor.
     * @param PipelinePermissionDeleteInputPort $interactor
     * @return void
     */
    public function __construct(PipelinePermissionDeleteInputPort $interactor)
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
        $viewModel = $this->interactor->delete(
            new PipelinePermissionDeleteRequestModel($request)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
