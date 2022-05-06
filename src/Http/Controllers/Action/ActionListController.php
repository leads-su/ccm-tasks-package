<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\Action;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Action\List\ActionListInputPort;
use ConsulConfigManager\Tasks\UseCases\Action\List\ActionListRequestModel;

/**
 * Class ActionListController
 * @package ConsulConfigManager\Tasks\Http\Controllers\Action
 */
class ActionListController extends Controller
{
    /**
     * Input port interactor instance
     * @var ActionListInputPort
     */
    private ActionListInputPort $interactor;

    /**
     * ActionListController constructor.
     * @param ActionListInputPort $interactor
     * @return void
     */
    public function __construct(ActionListInputPort $interactor)
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
        $viewModel = $this->interactor->list(
            new ActionListRequestModel($request)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
