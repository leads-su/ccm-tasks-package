<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\Action;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Action\Restore\ActionRestoreInputPort;
use ConsulConfigManager\Tasks\UseCases\Action\Restore\ActionRestoreRequestModel;

/**
 * Class ActionRestoreController
 * @package ConsulConfigManager\Tasks\Http\Controllers\Action
 */
class ActionRestoreController extends Controller
{
    /**
     * Input port interactor instance
     * @var ActionRestoreInputPort
     */
    private ActionRestoreInputPort $interactor;

    /**
     * ActionRestoreController constructor.
     * @param ActionRestoreInputPort $interactor
     * @return void
     */
    public function __construct(ActionRestoreInputPort $interactor)
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
        $viewModel = $this->interactor->restore(
            new ActionRestoreRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
