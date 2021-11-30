<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\Task;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Task\Delete\TaskDeleteInputPort;
use ConsulConfigManager\Tasks\UseCases\Task\Delete\TaskDeleteRequestModel;

/**
 * Class TaskDeleteController
 * @package ConsulConfigManager\Tasks\Http\Controllers\Task
 */
class TaskDeleteController extends Controller
{
    /**
     * Input port interactor instance
     * @var TaskDeleteInputPort
     */
    private TaskDeleteInputPort $interactor;

    /**
     * TaskDeleteController constructor.
     * @param TaskDeleteInputPort $interactor
     * @return void
     */
    public function __construct(TaskDeleteInputPort $interactor)
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
            new TaskDeleteRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
