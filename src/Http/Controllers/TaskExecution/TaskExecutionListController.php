<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\TaskExecution;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\TaskExecution\List\TaskExecutionListInputPort;
use ConsulConfigManager\Tasks\UseCases\TaskExecution\List\TaskExecutionListRequestModel;

/**
 * Class TaskExecutionListController
 * @package ConsulConfigManager\Tasks\Http\Controllers\TaskExecution
 */
class TaskExecutionListController extends Controller
{
    /**
     * Input port interactor instance
     * @var TaskExecutionListInputPort
     */
    private TaskExecutionListInputPort $interactor;

    /**
     * TaskExecutionListController constructor.
     * @param TaskExecutionListInputPort $interactor
     * @return void
     */
    public function __construct(TaskExecutionListInputPort $interactor)
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
            new TaskExecutionListRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
