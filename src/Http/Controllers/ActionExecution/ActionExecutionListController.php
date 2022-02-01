<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\ActionExecution;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\ActionExecution\List\ActionExecutionListInputPort;
use ConsulConfigManager\Tasks\UseCases\ActionExecution\List\ActionExecutionListRequestModel;

/**
 * Class ActionExecutionListController
 * @package ConsulConfigManager\Tasks\Http\Controllers\ActionExecution
 */
class ActionExecutionListController extends Controller {

    /**
     * Input port interactor instance
     * @var ActionExecutionListInputPort
     */
    private ActionExecutionListInputPort $interactor;

    /**
     * ActionExecutionListController constructor.
     * @param ActionExecutionListInputPort $interactor
     * @return void
     */
    public function __construct(ActionExecutionListInputPort $interactor) {
        $this->interactor = $interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param Request $request
     * @param string $identifier
     * @return Response|null
     */
    public function __invoke(Request $request, string $identifier): ?Response {
        $viewModel = $this->interactor->list(
            new ActionExecutionListRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd

}
