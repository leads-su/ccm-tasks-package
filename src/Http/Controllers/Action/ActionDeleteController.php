<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\Action;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Action\Delete\ActionDeleteInputPort;
use ConsulConfigManager\Tasks\UseCases\Action\Delete\ActionDeleteRequestModel;

/**
 * Class ActionDeleteController
 * @package ConsulConfigManager\Tasks\Http\Controllers\Action
 */
class ActionDeleteController extends Controller
{
    /**
     * Input port interactor instance
     * @var ActionDeleteInputPort
     */
    private ActionDeleteInputPort $interactor;

    /**
     * ActionDeleteController constructor.
     * @param ActionDeleteInputPort $interactor
     * @return void
     */
    public function __construct(ActionDeleteInputPort $interactor)
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
        $viewModel = $this->interactor->delete(
            new ActionDeleteRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
