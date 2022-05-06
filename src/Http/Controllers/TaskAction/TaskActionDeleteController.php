<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\TaskAction;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Delete\TaskActionDeleteInputPort;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Delete\TaskActionDeleteRequestModel;

/**
 * Class TaskActionDeleteController
 * @package ConsulConfigManager\Tasks\Http\Controllers\TaskAction
 */
class TaskActionDeleteController extends Controller
{
    /**
     * Input port interactor instance
     * @var TaskActionDeleteInputPort
     */
    private TaskActionDeleteInputPort $interactor;

    /**
     * TaskActionDeleteController constructor.
     * @param TaskActionDeleteInputPort $interactor
     * @return void
     */
    public function __construct(TaskActionDeleteInputPort $interactor)
    {
        $this->interactor = $interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param Request $request
     * @param string $taskIdentifier
     * @param string $actionIdentifier
     * @return Response|null
     */
    public function __invoke(Request $request, string $taskIdentifier, string $actionIdentifier): ?Response
    {
        $viewModel = $this->interactor->delete(
            new TaskActionDeleteRequestModel($request, $taskIdentifier, $actionIdentifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
