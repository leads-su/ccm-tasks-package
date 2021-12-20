<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Update;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;

/**
 * Class TaskUpdateInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Task\Update
 */
class TaskUpdateInteractor implements TaskUpdateInputPort
{
    /**
     * Output port instance
     * @var TaskUpdateOutputPort
     */
    private TaskUpdateOutputPort $output;

    /**
     * Repository instance
     * @var TaskRepositoryInterface
     */
    private TaskRepositoryInterface $repository;

    /**
     * TaskUpdateInteractor constructor.
     * @param TaskUpdateOutputPort $output
     * @param TaskRepositoryInterface $repository
     * @return void
     */
    public function __construct(
        TaskUpdateOutputPort $output,
        TaskRepositoryInterface $repository,
    ) {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function update(TaskUpdateRequestModel $requestModel): ViewModel
    {
        $request = $requestModel->getRequest();

        try {
            $model = $this->repository->findByManyOrFail(
                fields: ['id', 'uuid'],
                value: $requestModel->getIdentifier(),
            );

            $entity = $this->repository->update(
                $model->getID(),
                $request->get('name'),
                $request->get('description'),
                $request->get('type'),
            );
            return $this->output->update(new TaskUpdateResponseModel($entity));
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new TaskUpdateResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new TaskUpdateResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
