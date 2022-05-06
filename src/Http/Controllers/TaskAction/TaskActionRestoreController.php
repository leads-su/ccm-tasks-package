<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\TaskAction;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Restore\TaskActionRestoreInputPort;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Restore\TaskActionRestoreRequestModel;

/**
 * Class TaskActionRestoreController
 * @package ConsulConfigManager\Tasks\Http\Controllers\TaskAction
 */
class TaskActionRestoreController extends Controller
{
    /**
     * Input port interactor instance
     * @var TaskActionRestoreInputPort
     */
    private TaskActionRestoreInputPort $interactor;

    /**
     * TaskActionRestoreController constructor.
     * @param TaskActionRestoreInputPort $interactor
     * @return void
     */
    public function __construct(TaskActionRestoreInputPort $interactor)
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
        $viewModel = $this->interactor->restore(
            new TaskActionRestoreRequestModel($request, $taskIdentifier, $actionIdentifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
