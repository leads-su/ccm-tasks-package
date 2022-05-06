<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\Task;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Task\Update\TaskUpdateInputPort;
use ConsulConfigManager\Tasks\Http\Requests\Task\TaskCreateUpdateRequest;
use ConsulConfigManager\Tasks\UseCases\Task\Update\TaskUpdateRequestModel;

/**
 * Class TaskUpdateController
 * @package ConsulConfigManager\Tasks\Http\Contollers\Task
 */
class TaskUpdateController extends Controller
{
    /**
     * Input port interactor instance
     * @var TaskUpdateInputPort
     */
    private TaskUpdateInputPort $interactor;

    /**
     * TaskUpdateController constructor.
     * @param TaskUpdateInputPort $interactor
     * @return void
     */
    public function __construct(TaskUpdateInputPort $interactor)
    {
        $this->interactor =$interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param TaskCreateUpdateRequest $request
     * @param string $identifier
     * @return Response|null
     */
    public function __invoke(TaskCreateUpdateRequest $request, string $identifier): ?Response
    {
        $viewModel = $this->interactor->update(
            new TaskUpdateRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
