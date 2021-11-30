<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;

/**
 * Class TaskListInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Task\List
 */
class TaskListInteractor implements TaskListInputPort
{
    /**
     * Output port instance
     * @var TaskListOutputPort
     */
    private TaskListOutputPort $output;

    /**
     * Repository instance
     * @var TaskRepositoryInterface
     */
    private TaskRepositoryInterface $repository;

    /**
     * TaskListInteractor constructor.
     * @param TaskListOutputPort $output
     * @param TaskRepositoryInterface $repository
     * @return void
     */
    public function __construct(TaskListOutputPort $output, TaskRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function list(TaskListRequestModel $requestModel): ViewModel
    {
        try {
            $tasks = $this->repository->all([
                'id', 'uuid',
                'name', 'description', 'type',
                'created_at', 'updated_at',
            ])->toArray();
            return $this->output->list(new TaskListResponseModel($tasks));
            // @codeCoverageIgnoreStart
        } catch (Throwable $exception) {
            return $this->output->internalServerError(new TaskListResponseModel(), $exception);
        }
        // @codeCoverageIgnoreEnd
    }
}
