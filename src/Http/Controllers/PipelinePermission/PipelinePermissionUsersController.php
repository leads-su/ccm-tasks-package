<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\PipelinePermission;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\Users\PipelinePermissionUsersInputPort;
use ConsulConfigManager\Tasks\UseCases\PipelinePermission\Users\PipelinePermissionUsersRequestModel;

/**
 * Class PipelinePermissionUsersController
 * @package ConsulConfigManager\Tasks\Http\Controllers\PipelinePermission
 */
class PipelinePermissionUsersController extends Controller
{
    /**
     * Input port interactor instance
     * @var PipelinePermissionUsersInputPort
     */
    private PipelinePermissionUsersInputPort $interactor;

    /**
     * PipelinePermissionUsersController constructor.
     * @param PipelinePermissionUsersInputPort $interactor
     * @return void
     */
    public function __construct(PipelinePermissionUsersInputPort $interactor)
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
        $viewModel = $this->interactor->users(
            new PipelinePermissionUsersRequestModel($request)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
