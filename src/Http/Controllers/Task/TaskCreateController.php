<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\Task;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\Task\Create\TaskCreateInputPort;
use ConsulConfigManager\Tasks\Http\Requests\Task\TaskCreateUpdateRequest;
use ConsulConfigManager\Tasks\UseCases\Task\Create\TaskCreateRequestModel;

/**
 * Class TaskCreateController
 * @package ConsulConfigManager\Tasks\Http\Contollers\Task
 */
class TaskCreateController extends Controller
{
    /**
     * Input port interactor instance
     * @var TaskCreateInputPort
     */
    private TaskCreateInputPort $interactor;

    /**
     * TaskCreateController constructor.
     * @param TaskCreateInputPort $interactor
     * @return void
     */
    public function __construct(TaskCreateInputPort $interactor)
    {
        $this->interactor =$interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param TaskCreateUpdateRequest $request
     * @return Response|null
     */
    public function __invoke(TaskCreateUpdateRequest $request): ?Response
    {
        $viewModel = $this->interactor->create(
            new TaskCreateRequestModel($request)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
