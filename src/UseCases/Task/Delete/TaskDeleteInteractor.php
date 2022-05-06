<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Delete;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;

/**
 * Class TaskDeleteInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Task\Delete
 */
class TaskDeleteInteractor implements TaskDeleteInputPort
{
    /**
     * Output port instance
     * @var TaskDeleteOutputPort
     */
    private TaskDeleteOutputPort $output;

    /**
     * Repository instance
     * @var TaskRepositoryInterface
     */
    private TaskRepositoryInterface $repository;

    /**
     * TaskDeleteInteractor constructor.
     * @param TaskDeleteOutputPort $output
     * @param TaskRepositoryInterface $repository
     * @return void
     */
    public function __construct(TaskDeleteOutputPort $output, TaskRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function delete(TaskDeleteRequestModel $requestModel): ViewModel
    {
        try {
            $task = $this->repository->findByManyOrFail(
                fields: ['id', 'uuid'],
                value: $requestModel->getIdentifier(),
            );
            $this->repository->delete($task->getID());
            return $this->output->delete(new TaskDeleteResponseModel());
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new TaskDeleteResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new TaskDeleteResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
