<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskExecution\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionRepositoryInterface;

/**
 * Class TaskExecutionListInteractor
 * @package ConsulConfigManager\Tasks\UseCases\TaskExecution\List
 */
class TaskExecutionListInteractor implements TaskExecutionListInputPort
{
    /**
     * Output port instance
     * @var TaskExecutionListOutputPort
     */
    private TaskExecutionListOutputPort $output;

    /**
     * Repository instance
     * @var TaskExecutionRepositoryInterface
     */
    private TaskExecutionRepositoryInterface $repository;

    /**
     * Task repository instance
     * @var TaskRepositoryInterface
     */
    private TaskRepositoryInterface $taskRepository;

    /**
     * TaskExecutionListInteractor constructor.
     * @param TaskExecutionListOutputPort $output
     * @param TaskExecutionRepositoryInterface $repository
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(
        TaskExecutionListOutputPort $output,
        TaskExecutionRepositoryInterface $repository,
        TaskRepositoryInterface $taskRepository,
    ) {
        $this->output = $output;
        $this->repository = $repository;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @inheritDoc
     */
    public function list(TaskExecutionListRequestModel $requestModel): ViewModel
    {
        try {
            $task = $this->taskRepository->findByManyOrFail(
                fields: [
                    'id',
                    'uuid',
                ],
                value: $requestModel->getIdentifier()
            );

            $executions = $this->repository->findManyBy(
                field: 'task_uuid',
                value: $task->getUuid()
            );

            return $this->output->list(new TaskExecutionListResponseModel(
                $executions->sortByDesc('id')->values()
            ));
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new TaskExecutionListResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new TaskExecutionListResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
