<?php

namespace ConsulConfigManager\Tasks\Http\Controllers\TaskAction;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Create\TaskActionCreateInputPort;
use ConsulConfigManager\Tasks\Http\Requests\TaskAction\TaskActionCreateUpdateRequest;
use ConsulConfigManager\Tasks\UseCases\TaskAction\Create\TaskActionCreateRequestModel;

/**
 * Class TaskActionCreateController
 * @package ConsulConfigManager\Tasks\Http\Contollers\TaskAction
 */
class TaskActionCreateController extends Controller
{
    /**
     * Input port interactor instance
     * @var TaskActionCreateInputPort
     */
    private TaskActionCreateInputPort $interactor;

    /**
     * TaskActionCreateController constructor.
     * @param TaskActionCreateInputPort $interactor
     * @return void
     */
    public function __construct(TaskActionCreateInputPort $interactor)
    {
        $this->interactor =$interactor;
    }

    // @codeCoverageIgnoreStart

    /**
     * Handle incoming request
     * @param TaskActionCreateUpdateRequest $request
     * @param string $identifier
     * @return Response|null
     */
    public function __invoke(TaskActionCreateUpdateRequest $request, string $identifier): ?Response
    {
        $viewModel = $this->interactor->create(
            new TaskActionCreateRequestModel($request, $identifier)
        );

        if ($viewModel instanceof HttpResponseViewModel) {
            return $viewModel->getResponse();
        }

        return null;
    }

    // @codeCoverageIgnoreEnd
}
