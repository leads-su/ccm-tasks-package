<?php

namespace ConsulConfigManager\Tasks\UseCases\TaskAction\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\TaskActionRepositoryInterface;

/**
 * Class TaskActionListInteractor
 * @package ConsulConfigManager\Tasks\UseCases\TaskAction\List
 */
class TaskActionListInteractor implements TaskActionListInputPort
{
    /**
     * Output port instance
     * @var TaskActionListOutputPort
     */
    private TaskActionListOutputPort $output;

    /**
     * Repository instance
     * @var TaskActionRepositoryInterface
     */
    private TaskActionRepositoryInterface $repository;

    /**
     * TaskActionListInteractor constructor.
     * @param TaskActionListOutputPort $output
     * @param TaskActionRepositoryInterface $repository
     * @return void
     */
    public function __construct(TaskActionListOutputPort $output, TaskActionRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function list(TaskActionListRequestModel $requestModel): ViewModel
    {
        try {
            $actions = $this->repository->list(
                $requestModel->getIdentifier(),
                $requestModel->getRequest()->get('with_deleted', false)
            );
            return $this->output->list(new TaskActionListResponseModel($actions));
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new TaskActionListResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new TaskActionListResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
