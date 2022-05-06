<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Get;

use Throwable;
use Illuminate\Support\Arr;
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
                append: ['history', 'actions_list_extended'],
                withDeleted: $requestModel->getRequest()->get('with_deleted', false),
            )->toArray();

            if (isset($task['actions_list'])) {
                $task['actions'] = Arr::get($task, 'actions_list');
                unset($task['actions_list']);
            }
            if (isset($task['actions_list_extended'])) {
                $task['actions'] = Arr::get($task, 'actions_list_extended');
                unset($task['actions_list_extended']);
            }

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
