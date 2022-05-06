<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Update;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\TaskActionRepositoryInterface;

/**
 * Class TaskActionUpdateInteractor
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Update
 */
class TaskActionUpdateInteractor implements TaskActionUpdateInputPort
{
    /**
     * Output port instance
     * @var TaskActionUpdateOutputPort
     */
    private TaskActionUpdateOutputPort $output;

    /**
     * Repository instance
     * @var TaskActionRepositoryInterface
     */
    private TaskActionRepositoryInterface $repository;

    /**
     * TaskActionUpdateInteractor constructor.
     * @param TaskActionUpdateOutputPort $output
     * @param TaskActionRepositoryInterface $repository
     * @return void
     */
    public function __construct(TaskActionUpdateOutputPort $output, TaskActionRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function update(TaskActionUpdateRequestModel $requestModel): ViewModel
    {
        $request = $requestModel->getRequest();

        try {
            $this->repository->update(
                taskIdentifier: $requestModel->getTask(),
                actionIdentifier: $requestModel->getAction(),
                order: $request->get('order'),
            );
            return $this->output->update(new TaskActionUpdateResponseModel());
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new TaskActionUpdateResponseModel(), $exception->getModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new TaskActionUpdateResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
