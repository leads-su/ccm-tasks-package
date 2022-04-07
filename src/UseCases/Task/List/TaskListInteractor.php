<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\List;

use Throwable;
use Illuminate\Support\Arr;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
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
            $tasks = $this->repository->all(
                columns: [
                    'id', 'uuid',
                    'name', 'description',
                    'created_at', 'updated_at', 'deleted_at',
                ],
                append: ['actions_list_extended'],
                withDeleted: $requestModel->getRequest()->get('with_deleted', false)
            )->map(function (TaskInterface $task): array {
                $task = $task->toArray();
                $task['actions'] = Arr::get($task, 'actions_list_extended');
                unset($task['actions_list_extended']);
                return $task;
            })->toArray();
            return $this->output->list(new TaskListResponseModel($tasks));
            // @codeCoverageIgnoreStart
        } catch (Throwable $exception) {
            return $this->output->internalServerError(new TaskListResponseModel(), $exception);
        }
        // @codeCoverageIgnoreEnd
    }
}
