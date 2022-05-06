<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\Action;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Action\Create\ActionCreateInputPort;
use ConsulConfigManager\Tasks\Http\Requests\Action\ActionCreateUpdateRequest;
use ConsulConfigManager\Tasks\UseCases\Action\Create\ActionCreateRequestModel;

/**
 * Class ActionCreateController
 * @package ConsulConfigManager\Tasks\Http\Contollers\Action
 */
class ActionCreateController extends Controller
{
    /**
     * Input port interactor instance
     * @var ActionCreateInputPort
     */
    private ActionCreateInputPort $interactor;

    /**
     * ActionCreateController constructor.
     * @param ActionCreateInputPort $interactor
     * @return void
     */
    public function __construct(ActionCreateInputPort $interactor)
    {
        $this->interactor =$interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param ActionCreateUpdateRequest $request
     * @return Response|null
     */
    public function __invoke(ActionCreateUpdateRequest $request): ?Response
    {
        $viewModel = $this->interactor->create(
            new ActionCreateRequestModel($request)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
