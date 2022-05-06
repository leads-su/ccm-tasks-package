<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\TaskExecution;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\TaskExecution\Get\TaskExecutionGetInputPort;
use ConsulConfigManager\Tasks\UseCases\TaskExecution\Get\TaskExecutionGetRequestModel;

/**
 * Class TaskExecutionGetController
 * @package ConsulConfigManager\Tasks\Http\Controllers\TaskExecution
 */
class TaskExecutionGetController extends Controller
{
    /**
     * Input port interactor instance
     * @var TaskExecutionGetInputPort
     */
    private TaskExecutionGetInputPort $interactor;

    /**
     * TaskExecutionGetController constructor.
     * @param TaskExecutionGetInputPort $interactor
     * @return void
     */
    public function __construct(TaskExecutionGetInputPort $interactor)
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
            new TaskExecutionGetRequestModel($request, $identifier, $execution)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
