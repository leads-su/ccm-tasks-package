<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Get;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\TaskActionRepositoryInterface;

/**
 * Class TaskActionGetInteractor
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Get
 */
class TaskActionGetInteractor implements TaskActionGetInputPort
{
    /**
     * Output port instance
     * @var TaskActionGetOutputPort
     */
    private TaskActionGetOutputPort $output;

    /**
     * Action repository instance
     * @var TaskActionRepositoryInterface
     */
    private TaskActionRepositoryInterface $repository;

    /**
     * TaskActionGetInteractor constructor.
     * @param TaskActionGetOutputPort $output
     * @param TaskActionRepositoryInterface $repository
     */
    public function __construct(TaskActionGetOutputPort $output, TaskActionRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function information(TaskActionGetRequestModel $requestModel): ViewModel
    {
        try {
            $model = $this->repository->get(
                $requestModel->getTaskIdentifier(),
                $requestModel->getActionIdentifier(),
                [
                    'task',
                    'action',
                ]
            );
            return $this->output->information(new TaskActionGetResponseModel($model));
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new TaskActionGetResponseModel(), $exception->getModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new TaskActionGetResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
