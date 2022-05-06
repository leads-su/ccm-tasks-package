<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskExecution\Get;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionRepositoryInterface;

/**
 * Class TaskExecutionGetInteractor
 * @package ConsulConfigManager\Tasks\UseCases\TaskExecution\Get
 */
class TaskExecutionGetInteractor implements TaskExecutionGetInputPort
{
    /**
     * Output port instance
     * @var TaskExecutionGetOutputPort
     */
    private TaskExecutionGetOutputPort $output;

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
     * TaskExecutionGetInteractor constructor.
     * @param TaskExecutionGetOutputPort $output
     * @param TaskExecutionRepositoryInterface $repository
     * @param TaskRepositoryInterface $taskRepository
     * @return void
     */
    public function __construct(TaskExecutionGetOutputPort $output, TaskExecutionRepositoryInterface $repository, TaskRepositoryInterface $taskRepository)
    {
        $this->output = $output;
        $this->repository = $repository;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @inheritDoc
     */
    public function get(TaskExecutionGetRequestModel $requestModel): ViewModel
    {
        try {
            $task = $this->taskRepository->findByManyOrFail(
                fields: [
                    'id',
                    'uuid',
                ],
                value: $requestModel->getIdentifier(),
            );

            $execution = $this->repository->findByManyFromMappingsOrFail(
                mappings: [
                    'id'            =>  $requestModel->getExecution(),
                    'task_uuid'     =>  $task->getUuid(),
                ],
                with: [
                    'pipeline'      =>  function ($query) {
                        $query->select(
                            'id',
                            'uuid',
                            'name',
                            'description',
                        );
                    },
                ]
            );

            return $this->output->get(new TaskExecutionGetResponseModel(
                $execution->toArray(),
            ));
        } catch (Throwable $throwable) {
            if ($throwable instanceof ModelNotFoundException) {
                return $this->output->notFound(new TaskExecutionGetResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new TaskExecutionGetResponseModel(), $throwable);
            // @codeCoverageIgnoreEnd
        }
    }
}
