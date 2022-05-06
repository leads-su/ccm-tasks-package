<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\Create;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Exceptions\ModelAlreadyExistsException;
use ConsulConfigManager\Tasks\Interfaces\TaskActionRepositoryInterface;

/**
 * Class TaskActionCreateInteractor
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\Create
 */
class TaskActionCreateInteractor implements TaskActionCreateInputPort
{
    /**
     * Output port instance
     * @var TaskActionCreateOutputPort
     */
    private TaskActionCreateOutputPort $output;

    /**
     * Repository instance
     * @var TaskActionRepositoryInterface
     */
    private TaskActionRepositoryInterface $repository;

    /**
     * TaskActionCreateInteractor constructor.
     * @param TaskActionCreateOutputPort $output
     * @param TaskActionRepositoryInterface $repository
     * @return void
     */
    public function __construct(TaskActionCreateOutputPort $output, TaskActionRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function create(TaskActionCreateRequestModel $requestModel): ViewModel
    {
        $request = $requestModel->getRequest();
        try {
            $this->repository->create(
                taskIdentifier: $requestModel->getTask(),
                actionIdentifier: $request->get('action_uuid'),
                order: $request->get('order'),
            );
            return $this->output->create(new TaskActionCreateResponseModel($this->repository->get(
                $requestModel->getTask(),
                $request->get('action_uuid')
            )));
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new TaskActionCreateResponseModel(), $exception->getModel());
            } elseif ($exception instanceof ModelAlreadyExistsException) {
                return $this->output->alreadyExists(new TaskActionCreateResponseModel(), $exception->getModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new TaskActionCreateResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
