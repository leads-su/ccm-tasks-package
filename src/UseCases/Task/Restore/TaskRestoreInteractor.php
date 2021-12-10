<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Restore;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;

/**
 * Class TaskRestoreInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Task\Restore
 */
class TaskRestoreInteractor implements TaskRestoreInputPort
{
    /**
     * Output port instance
     * @var TaskRestoreOutputPort
     */
    private TaskRestoreOutputPort $output;

    /**
     * Repository instance
     * @var TaskRepositoryInterface
     */
    private TaskRepositoryInterface $repository;

    /**
     * TaskRestoreInteractor constructor.
     * @param TaskRestoreOutputPort $output
     * @param TaskRepositoryInterface $repository
     * @return void
     */
    public function __construct(TaskRestoreOutputPort $output, TaskRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function restore(TaskRestoreRequestModel $requestModel): ViewModel
    {
        try {
            $task = $this->repository->findByManyOrFail(['id', 'uuid'], $requestModel->getIdentifier(), ['*'], true);
            $this->repository->restore($task->getID());
            return $this->output->restore(new TaskRestoreResponseModel());
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new TaskRestoreResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new TaskRestoreResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
