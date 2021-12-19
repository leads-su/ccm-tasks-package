<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Restore;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\TaskActionRepositoryInterface;

/**
 * Class TaskActionRestoreInteractor
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Restore
 */
class TaskActionRestoreInteractor implements TaskActionRestoreInputPort
{
    /**
     * Output port instance
     * @var TaskActionRestoreOutputPort
     */
    private TaskActionRestoreOutputPort $output;

    /**
     * Repository instance
     * @var TaskActionRepositoryInterface
     */
    private TaskActionRepositoryInterface $repository;


    /**
     * TaskActionRestoreInteractor constructor.
     * @param TaskActionRestoreOutputPort $output
     * @param TaskActionRepositoryInterface $repository
     */
    public function __construct(TaskActionRestoreOutputPort $output, TaskActionRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function restore(TaskActionRestoreRequestModel $requestModel): ViewModel
    {
        try {
            $this->repository->restore($requestModel->getTaskIdentifier(), $requestModel->getActionIdentifier());
            return $this->output->restore(new TaskActionRestoreResponseModel());
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new TaskActionRestoreResponseModel(), $exception->getModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new TaskActionRestoreResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
