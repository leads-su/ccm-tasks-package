<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\TaskAction;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Get\TaskActionGetInputPort;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Get\TaskActionGetRequestModel;

/**
 * Class TaskActionGetController
 * @package ConsulConfigManager\Tasks\Http\Controllers\TaskAction
 */
class TaskActionGetController extends Controller
{
    /**
     * Input port interactor instance
     * @var TaskActionGetInputPort
     */
    private TaskActionGetInputPort $interactor;

    /**
     * TaskActionGetController constructor.
     * @param TaskActionGetInputPort $interactor
     * @return void
     */
    public function __construct(TaskActionGetInputPort $interactor)
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
        $viewModel = $this->interactor->information(
            new TaskActionGetRequestModel($request, $taskIdentifier, $actionIdentifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
