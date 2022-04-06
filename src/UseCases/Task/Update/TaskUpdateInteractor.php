<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Update;

use Throwable;
use Illuminate\Support\Arr;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\TaskActionInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskActionRepositoryInterface;

/**
 * Class TaskUpdateInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Task\Update
 */
class TaskUpdateInteractor implements TaskUpdateInputPort
{
    /**
     * Output port instance
     * @var TaskUpdateOutputPort
     */
    private TaskUpdateOutputPort $output;

    /**
     * Repository instance
     * @var TaskRepositoryInterface
     */
    private TaskRepositoryInterface $repository;

    /**
     * Task Action repository instance
     * @var TaskActionRepositoryInterface
     */
    private TaskActionRepositoryInterface $taskActionRepository;

    /**
     * TaskUpdateInteractor constructor.
     * @param TaskUpdateOutputPort $output
     * @param TaskRepositoryInterface $repository
     * @param TaskActionRepositoryInterface $taskActionRepository
     */
    public function __construct(
        TaskUpdateOutputPort $output,
        TaskRepositoryInterface $repository,
        TaskActionRepositoryInterface $taskActionRepository,
    ) {
        $this->output = $output;
        $this->repository = $repository;
        $this->taskActionRepository = $taskActionRepository;
    }

    /**
     * @inheritDoc
     */
    public function update(TaskUpdateRequestModel $requestModel): ViewModel
    {
        $request = $requestModel->getRequest();

        try {
            $model = $this->repository->findByManyOrFail(
                fields: ['id', 'uuid'],
                value: $requestModel->getIdentifier(),
            );

            $entity = $this->repository->update(
                $model->getID(),
                $request->get('name'),
                $request->get('description'),
                $request->get('fail_on_error', false),
            );
            $this->createOrUpdateActionsRelations($entity, $request->get('actions', []));
            return $this->output->update(new TaskUpdateResponseModel($entity));
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new TaskUpdateResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new TaskUpdateResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Create or update relations between task and actions
     * @param TaskInterface $task
     * @param array $actions
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function createOrUpdateActionsRelations(TaskInterface $task, array $actions): void
    {
        $taskIdentifier = $task->getUuid();

        $databaseActions = $this->taskActionRepository
            ->getTaskActions($taskIdentifier)
            ->map(function (TaskActionInterface $taskAction): string {
                return $taskAction->getActionUuid();
            })
            ->toArray();


        $processedActions = [];

        foreach ($actions as $index => $action) {
            $actionIdentifier = Arr::get($action, 'uuid');
            $processedActions[] = $actionIdentifier;
            $order = $index + 1;

            if ($this->taskActionRepository->exists($taskIdentifier, $actionIdentifier)) {
                $this->taskActionRepository->update(
                    taskIdentifier: $taskIdentifier,
                    actionIdentifier: $actionIdentifier,
                    order: $order,
                );
            } else {
                $this->taskActionRepository->create(
                    taskIdentifier: $taskIdentifier,
                    actionIdentifier: $actionIdentifier,
                    order: $order,
                );
            }
        }

        foreach (array_diff($databaseActions, $processedActions) as $action) {
            $this->taskActionRepository->forceDelete($taskIdentifier, $action);
        }
    }
}
