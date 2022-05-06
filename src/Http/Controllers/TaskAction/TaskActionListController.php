<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\TaskAction;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\TaskAction\List\TaskActionListInputPort;
use ConsulConfigManager\Tasks\UseCases\TaskAction\List\TaskActionListRequestModel;

/**
 * Class TaskActionListController
 * @package ConsulConfigManager\Tasks\Http\Controllers\TaskAction
 */
class TaskActionListController extends Controller
{
    /**
     * Input port interactor instance
     * @var TaskActionListInputPort
     */
    private TaskActionListInputPort $interactor;

    /**
     * TaskActionListController constructor.
     * @param TaskActionListInputPort $interactor
     * @return void
     */
    public function __construct(TaskActionListInputPort $interactor)
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
        $viewModel = $this->interactor->list(
            new TaskActionListRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
