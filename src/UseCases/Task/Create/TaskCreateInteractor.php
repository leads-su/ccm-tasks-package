<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Create;

use Throwable;
use Illuminate\Support\Arr;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskActionRepositoryInterface;

/**
 * Class TaskCreateInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Task\Create
 */
class TaskCreateInteractor implements TaskCreateInputPort
{
    /**
     * Output port instance
     * @var TaskCreateOutputPort
     */
    private TaskCreateOutputPort $output;

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
     * TaskCreateInteractor constructor.
     * @param TaskCreateOutputPort $output
     * @param TaskRepositoryInterface $repository
     * @param TaskActionRepositoryInterface $taskActionRepository
     */
    public function __construct(
        TaskCreateOutputPort $output,
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
    public function create(TaskCreateRequestModel $requestModel): ViewModel
    {
        $request = $requestModel->getRequest();

        try {
            $entity = $this->repository->create(
                $request->get('name'),
                $request->get('description'),
                $request->get('type'),
                $request->get('fail_on_error', false),
            );
            $this->createActionsRelations($entity, $request->get('actions', []));
            return $this->output->create(new TaskCreateResponseModel($entity));
            // @codeCoverageIgnoreStart
        } catch (Throwable $exception) {
            return $this->output->internalServerError(new TaskCreateResponseModel(), $exception);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Create or update relations between task and actions
     * @param TaskInterface $task
     * @param array $actions
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function createActionsRelations(TaskInterface $task, array $actions): void
    {
        $taskIdentifier = $task->getUuid();

        foreach ($actions as $index => $action) {
            $actionIdentifier = Arr::get($action, 'uuid');
            $order = $index + 1;

            $this->taskActionRepository->create(
                taskIdentifier: $taskIdentifier,
                actionIdentifier: $actionIdentifier,
                order: $order,
            );
        }
    }
}
