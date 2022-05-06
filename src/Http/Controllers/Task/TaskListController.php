<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\Task;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Task\List\TaskListInputPort;
use ConsulConfigManager\Tasks\UseCases\Task\List\TaskListRequestModel;

/**
 * Class TaskListController
 * @package ConsulConfigManager\Tasks\Http\Controllers\Task
 */
class TaskListController extends Controller
{
    /**
     * Input port interactor instance
     * @var TaskListInputPort
     */
    private TaskListInputPort $interactor;

    /**
     * TaskListController constructor.
     * @param TaskListInputPort $interactor
     * @return void
     */
    public function __construct(TaskListInputPort $interactor)
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
            new TaskListRequestModel($request)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
