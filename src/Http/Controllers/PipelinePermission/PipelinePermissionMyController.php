<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\PipelinePermission;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\My\PipelinePermissionMyInputPort;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\My\PipelinePermissionMyRequestModel;

/**
 * Class PipelinePermissionMyController
 * @package ConsulConfigManager\Tasks\Http\Controllers\PipelinePermission
 */
class PipelinePermissionMyController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelinePermissionMyInputPort
     */
    private PipelinePermissionMyInputPort $interactor;

    /**
     * PipelinePermissionMyController constructor.
     * @param PipelinePermissionMyInputPort $interactor
     * @return void
     */
    public function __construct(PipelinePermissionMyInputPort $interactor)
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
        $viewModel = $this->interactor->my(
            new PipelinePermissionMyRequestModel($request)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
