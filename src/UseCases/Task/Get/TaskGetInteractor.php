<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Get;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;

/**
 * Class TaskGetInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Task\Get
 */
class TaskGetInteractor implements TaskGetInputPort
{
    /**
     * Output port instance
     * @var TaskGetOutputPort
     */
    private TaskGetOutputPort $output;

    /**
     * Repository instance
     * @var TaskRepositoryInterface
     */
    private TaskRepositoryInterface $repository;

    /**
     * TaskGetInteractor constructor.
     * @param TaskGetOutputPort $output
     * @param TaskRepositoryInterface $repository
     * @return void
     */
    public function __construct(TaskGetOutputPort $output, TaskRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function get(TaskGetRequestModel $requestModel): ViewModel
    {
        try {
            $task = $this->repository->findByManyOrFail(
                fields: ['id', 'uuid'],
                value: $requestModel->getIdentifier(),
                append: ['history'],
                withDeleted: $requestModel->getRequest()->get('with_deleted', false),
            );
            return $this->output->get(new TaskGetResponseModel($task));
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new TaskGetResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new TaskGetResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
