<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Delete;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\TaskActionRepositoryInterface;

/**
 * Class TaskActionDeleteInteractor
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Delete
 */
class TaskActionDeleteInteractor implements TaskActionDeleteInputPort
{
    /**
     * Output port instance
     * @var TaskActionDeleteOutputPort
     */
    private TaskActionDeleteOutputPort $output;

    /**
     * Repository instance
     * @var TaskActionRepositoryInterface
     */
    private TaskActionRepositoryInterface $repository;


    /**
     * TaskActionDeleteInteractor constructor.
     * @param TaskActionDeleteOutputPort $output
     * @param TaskActionRepositoryInterface $repository
     */
    public function __construct(TaskActionDeleteOutputPort $output, TaskActionRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function delete(TaskActionDeleteRequestModel $requestModel): ViewModel
    {
        try {
            $this->repository->delete($requestModel->getTaskIdentifier(), $requestModel->getActionIdentifier());
            return $this->output->delete(new TaskActionDeleteResponseModel());
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new TaskActionDeleteResponseModel(), $exception->getModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new TaskActionDeleteResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
