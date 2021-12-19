<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\TaskAction;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Update\TaskActionUpdateInputPort;
use ConsulConfigManager\Tasks\Http\Requests\TaskAction\TaskActionCreateUpdateRequest;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Update\TaskActionUpdateRequestModel;

/**
 * Class TaskActionUpdateController
 * @package ConsulConfigManager\Tasks\Http\Contollers\TaskAction
 */
class TaskActionUpdateController extends Controller
{
    /**
     * Input port interactor instance
     * @var TaskActionUpdateInputPort
     */
    private TaskActionUpdateInputPort $interactor;

    /**
     * TaskActionUpdateController constructor.
     * @param TaskActionUpdateInputPort $interactor
     * @return void
     */
    public function __construct(TaskActionUpdateInputPort $interactor)
    {
        $this->interactor =$interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param TaskActionCreateUpdateRequest $request
     * @param string $taskIdentifier
     * @param string $actionIdentifier
     * @return Response|null
     */
    public function __invoke(TaskActionCreateUpdateRequest $request, string $taskIdentifier, string $actionIdentifier): ?Response
    {
        $viewModel = $this->interactor->update(
            new TaskActionUpdateRequestModel($request, $taskIdentifier, $actionIdentifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
