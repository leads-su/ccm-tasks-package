<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\Action;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Action\Get\ActionGetInputPort;
use ConsulConfigManager\Tasks\UseCases\Action\Get\ActionGetRequestModel;

/**
 * Class ActionGetController
 * @package ConsulConfigManager\Tasks\Http\Controllers\Action
 */
class ActionGetController extends Controller
{
    /**
     * Input port interactor instance
     * @var ActionGetInputPort
     */
    private ActionGetInputPort $interactor;

    /**
     * ActionGetController constructor.
     * @param ActionGetInputPort $interactor
     * @return void
     */
    public function __construct(ActionGetInputPort $interactor)
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
        $viewModel = $this->interactor->get(
            new ActionGetRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
