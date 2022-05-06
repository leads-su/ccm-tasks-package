<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\PipelinePermission;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\List\PipelinePermissionListInputPort;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\List\PipelinePermissionListRequestModel;

/**
 * Class PipelinePermissionListController
 * @package ConsulConfigManager\Tasks\Http\Controllers\PipelinePermission
 */
class PipelinePermissionListController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelinePermissionListInputPort
     */
    private PipelinePermissionListInputPort $interactor;

    /**
     * PipelinePermissionListController constructor.
     * @param PipelinePermissionListInputPort $interactor
     * @return void
     */
    public function __construct(PipelinePermissionListInputPort $interactor)
    {
        $this->interactor = $interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param Request $request
     * @param string|int $identifier
     * @return Response|null
     */
    public function __invoke(Request $request, string|int $identifier): ?Response
    {
        $viewModel = $this->interactor->list(
            new PipelinePermissionListRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
