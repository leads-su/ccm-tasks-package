<?php

namespace ConsulConfigManager\Tasks\Repositories;

use Illuminate\Support\Str;
use ConsulConfigManager\Tasks\Models\Task;
use ConsulConfigManager\Tasks\Models\Action;
use Illuminate\Database\Eloquent\Collection;
use ConsulConfigManager\Tasks\Models\TaskAction;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskActionInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;
use ConsulConfigManager\Tasks\AggregateRoots\TaskActionAggregateRoot;
use ConsulConfigManager\Tasks\Exceptions\ModelAlreadyExistsException;
use ConsulConfigManager\Tasks\Interfaces\TaskActionRepositoryInterface;

/**
 * Class TaskActionRepository
 * @package ConsulConfigManager\Tasks\Repositories
 */
class TaskActionRepository implements TaskActionRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function list(int|string $taskIdentifier, array $with = [], array $append = [], bool $withDeleted = false): Collection
    {
        $with['action'] = function ($query): void {
            $query->select(['id', 'uuid', 'name', 'description', 'type']);
        };

        return TaskAction::withTrashed($withDeleted)
            ->with($with)
            ->where('task_uuid', '=', $this->resolveTaskUUID($taskIdentifier, $withDeleted))
            ->get()->each->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function get(int|string $taskIdentifier, int|string $actionIdentifier, array $with = [], array $append = []): TaskActionInterface
    {
        return $this->resolveTaskAction(
            taskIdentifier: $taskIdentifier,
            actionIdentifier: $actionIdentifier,
            with: $with,
            append: $append
        );
    }

    /**
     * @@inheritDoc
     */
    public function exists(string|int $taskIdentifier, string|int $actionIdentifier): bool
    {
        $taskIdentifier = $this->resolveTaskUUID($taskIdentifier);
        $actionIdentifier = $this->resolveActionUUID($actionIdentifier);

        return TaskAction::withTrashed(true)
            ->where('task_uuid', '=', $taskIdentifier)
            ->where('action_uuid', '=', $actionIdentifier)
            ->exists();
    }

    /**
     * @inheritDoc
     */
    public function create(string|int $taskIdentifier, string|int $actionIdentifier, int $order): bool
    {
        $uuid = Str::uuid()->toString();
        $taskIdentifier = $this->resolveTaskUUID($taskIdentifier);
        $actionIdentifier = $this->resolveActionUUID($actionIdentifier);

        if ($this->exists($taskIdentifier, $actionIdentifier)) {
            throw (new ModelAlreadyExistsException())->setModel(TaskAction::class);
        }

        TaskActionAggregateRoot::retrieve($uuid)
            ->createEntity(
                $taskIdentifier,
                $actionIdentifier,
                $order,
            )->persist();
        return true;
    }

    /**
     * @inheritDoc
     */
    public function update(string|int $taskIdentifier, string|int $actionIdentifier, int $order): bool
    {
        $this->resolveTaskActionAggregateRoot(
            taskIdentifier: $taskIdentifier,
            actionIdentifier: $actionIdentifier
        )
            ->updateEntity($order)
            ->persist();
        return true;
    }

    /**
     * @inheritDoc
     */
    public function delete(string|int $taskIdentifier, string|int $actionIdentifier, bool $force = false): bool
    {
        $this->resolveTaskActionAggregateRoot(
            taskIdentifier: $taskIdentifier,
            actionIdentifier: $actionIdentifier
        )
            ->deleteEntity($force)
            ->persist();
        return true;
    }

    /**
     * @inheritDoc
     */
    public function forceDelete(string|int $taskIdentifier, string|int $actionIdentifier): bool
    {
        return $this->delete(
            taskIdentifier: $taskIdentifier,
            actionIdentifier: $actionIdentifier,
            force: true,
        );
    }

    /**
     * @inheritDoc
     */
    public function restore(string|int $taskIdentifier, string|int $actionIdentifier): bool
    {
        $this->resolveTaskActionAggregateRoot(
            taskIdentifier: $taskIdentifier,
            actionIdentifier: $actionIdentifier
        )
            ->restoreEntity()
            ->persist();
        return true;
    }

    /**
     * Resolve task action by specified parameters
     * @param string|int $taskIdentifier
     * @param string|int $actionIdentifier
     * @param array $columns
     * @param array $with
     * @param array $append
     * @return TaskActionInterface
     * @throws BindingResolutionException
     */
    public function resolveTaskAction(string|int $taskIdentifier, string|int $actionIdentifier, array $columns = ['*'], array $with = [], array $append = []): TaskActionInterface
    {
        $taskIdentifier = $this->resolveTaskUUID($taskIdentifier);
        $actionIdentifier = $this->resolveActionUUID($actionIdentifier);
        return $this->findTaskAction(
            taskIdentifier: $taskIdentifier,
            actionIdentifier: $actionIdentifier,
            columns: $columns,
            with: $with,
            append: $append
        );
    }

    /**
     * Resolve task action aggregate root
     * @param string|int $taskIdentifier
     * @param string|int $actionIdentifier
     * @return TaskActionAggregateRoot
     * @throws BindingResolutionException
     */
    public function resolveTaskActionAggregateRoot(string|int $taskIdentifier, string|int $actionIdentifier): TaskActionAggregateRoot
    {
        $taskActionInstance = $this->resolveTaskAction(
            taskIdentifier: $taskIdentifier,
            actionIdentifier: $actionIdentifier,
            columns: ['uuid']
        );
        return TaskActionAggregateRoot::retrieve($taskActionInstance->getUuid());
    }

    /**
     * @inheritDoc
     */
    public function findTask(string|int $id, array $columns = ['*'], bool $withDeleted = false): TaskInterface
    {
        return $this->taskRepository()
            ->findByManyOrFail(
                fields: ['id', 'uuid'],
                value: $id,
                columns: $columns,
                withDeleted: $withDeleted
            );
    }

    /**
     * @inheritDoc
     */
    public function getTaskActions(string|int $identifier): Collection
    {
        $taskIdentifier = $this->resolveTaskUUID($identifier);
        return TaskAction::withTrashed(true)->where('task_uuid', '=', $taskIdentifier)->get();
    }

    /**
     * @inheritDoc
     */
    public function findAction(string|int $id, array $columns = ['*'], bool $withDeleted = false): ActionInterface
    {
        return $this->actionRepository()
            ->findByManyOrFail(
                fields: ['id', 'uuid'],
                value: $id,
                columns: $columns,
                withDeleted: $withDeleted
            );
    }

    /**
     * Find task action by specified parameters
     * @param string $taskIdentifier
     * @param string $actionIdentifier
     * @param array $columns
     * @param array $with
     * @param array $append
     * @return TaskActionInterface
     */
    public function findTaskAction(string $taskIdentifier, string $actionIdentifier, array $columns = ['*'], array $with = [], array $append = []): TaskActionInterface
    {
        return TaskAction::withTrashed(true)
            ->where('task_uuid', '=', $taskIdentifier)
            ->where('action_uuid', '=', $actionIdentifier)
            ->with($with)
            ->firstOrFail($columns)->setAppends($append);
    }


    /**
     * @inheritDoc
     */
    public function resolveTaskUUID(string|int $id, bool $withDeleted = false): string
    {
        if (is_string($id) && is_numeric($id) || is_integer($id)) {
            $id = $this->findTask($id, ['uuid'], $withDeleted)->getUuid();
        }
        if (!$this->taskExists($id)) {
            throw (new ModelNotFoundException())->setModel(Task::class);
        }
        return $id;
    }

    /**
     * @inheritDoc
     */
    public function resolveActionUUID(string|int $id, bool $withDeleted = false): string
    {
        if (is_numeric($id) || is_integer($id)) {
            $id = $this->findAction($id, ['uuid'], $withDeleted)->getUuid();
        }
        if (!$this->actionExists($id)) {
            throw (new ModelNotFoundException())->setModel(Action::class);
        }
        return $id;
    }

    /**
     * @inheritDoc
     */
    public function isBound(string|int $taskID, string|int $actionID, bool $withDeleted = false, bool $return = false): bool|TaskActionInterface
    {
        $query = TaskAction::withTrashed($withDeleted)
            ->where('task_uuid', '=', $this->resolveTaskUUID($taskID))
            ->where('action_uuid', '=', $this->resolveActionUUID($actionID));

        if ($return) {
            return $query->firstOrFail();
        }
        return $query->exists();
    }

    /**
     * @inheritDoc
     */
    public function taskActionUUID(string|int $taskID, string|int $actionID, bool $withDeleted = false): string
    {
        try {
            $model = $this->isBound($taskID, $actionID, $withDeleted, true);
            return $model->getUuid();
        } catch (ModelNotFoundException) {
            return '';
        }
    }

    /**
     * @inheritDoc
     */
    public function taskExists(string $taskID): bool
    {
        return $this->taskRepository()->findBy(
            field: 'uuid',
            value: $taskID,
        ) !== null;
    }

    /**
     * @inheritDoc
     */
    public function actionExists(string $actionID): bool
    {
        return $this->actionRepository()->findBy(
            field: 'uuid',
            value: $actionID
        ) !== null;
    }

    /**
     * Get instance of task repository
     * @return TaskRepositoryInterface
     * @throws BindingResolutionException
     */
    private function taskRepository(): TaskRepositoryInterface
    {
        return app()->make(TaskRepositoryInterface::class);
    }

    /**
     * Get instance of action repository
     * @return ActionRepositoryInterface
     * @throws BindingResolutionException
     */
    private function actionRepository(): ActionRepositoryInterface
    {
        return app()->make(ActionRepositoryInterface::class);
    }
}
