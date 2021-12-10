<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\Task;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Task\Restore\TaskRestoreInputPort;
use ConsulConfigManager\Tasks\UseCases\Task\Restore\TaskRestoreRequestModel;

/**
 * Class TaskRestoreController
 * @package ConsulConfigManager\Tasks\Http\Controllers\Task
 */
class TaskRestoreController extends Controller
{
    /**
     * Input port interactor instance
     * @var TaskRestoreInputPort
     */
    private TaskRestoreInputPort $interactor;

    /**
     * TaskRestoreController constructor.
     * @param TaskRestoreInputPort $interactor
     * @return void
     */
    public function __construct(TaskRestoreInputPort $interactor)
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
        $viewModel = $this->interactor->restore(
            new TaskRestoreRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
