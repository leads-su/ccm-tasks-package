<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\Action;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Action\Update\ActionUpdateInputPort;
use ConsulConfigManager\Tasks\Http\Requests\Action\ActionCreateUpdateRequest;
use ConsulConfigManager\Tasks\UseCases\Action\Update\ActionUpdateRequestModel;

/**
 * Class ActionUpdateController
 * @package ConsulConfigManager\Tasks\Http\Contollers\Action
 */
class ActionUpdateController extends Controller
{
    /**
     * Input port interactor instance
     * @var ActionUpdateInputPort
     */
    private ActionUpdateInputPort $interactor;

    /**
     * ActionUpdateController constructor.
     * @param ActionUpdateInputPort $interactor
     * @return void
     */
    public function __construct(ActionUpdateInputPort $interactor)
    {
        $this->interactor =$interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param ActionCreateUpdateRequest $request
     * @param string $identifier
     * @return Response|null
     */
    public function __invoke(ActionCreateUpdateRequest $request, string $identifier): ?Response
    {
        $viewModel = $this->interactor->update(
            new ActionUpdateRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
