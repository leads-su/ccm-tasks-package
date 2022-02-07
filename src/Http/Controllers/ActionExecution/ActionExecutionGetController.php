<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\ActionExecution;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\ActionExecution\Get\ActionExecutionGetInputPort;
use ConsulConfigManager\Tasks\UseCases\ActionExecution\Get\ActionExecutionGetRequestModel;

/**
 * Class ActionExecutionGetController
 * @package ConsulConfigManager\Tasks\Http\Controllers\ActionExecution
 */
class ActionExecutionGetController extends Controller
{
    /**
     * Input port interactor instance
     * @var ActionExecutionGetInputPort
     */
    private ActionExecutionGetInputPort $interactor;

    /**
     * ActionExecutionGetController constructor.
     * @param ActionExecutionGetInputPort $interactor
     * @return void
     */
    public function __construct(ActionExecutionGetInputPort $interactor)
    {
        $this->interactor = $interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param Request $request
     * @param string $identifier
     * @param int $execution
     * @return Response|null
     */
    public function __invoke(Request $request, string $identifier, int $execution): ?Response
    {
        $viewModel = $this->interactor->get(
            new ActionExecutionGetRequestModel($request, $identifier, $execution)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
