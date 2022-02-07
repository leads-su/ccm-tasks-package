<?php

namespace ConsulConfigManager\Tasks\Services\TaskRunner\Entities;

use Throwable;
use Illuminate\Support\Collection;
use ConsulConfigManager\Tasks\Models\Task;
use Illuminate\Contracts\Support\Arrayable;
use ConsulConfigManager\Tasks\Models\TaskExecution;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionInterface;
use ConsulConfigManager\Tasks\Services\TaskRunner\LoggableClass;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionRepositoryInterface;

/**
 * Class TaskEntity
 * @package ConsulConfigManager\Tasks\Services\TaskRunner\Entities
 */
class TaskEntity extends LoggableClass implements Arrayable
{
    /**
     * Task Execution instance
     * @var TaskExecutionInterface
     */
    private TaskExecutionInterface $execution;

    /**
     * Task instance
     * @var TaskInterface
     */
    private TaskInterface $task;

    /**
     * Collection of servers for task
     * @var Collection|ServerEntity[]|array
     */
    private Collection $servers;

    /**
     * TaskEntity constructor.
     * @param TaskExecutionInterface $execution
     * @return void
     */
    public function __construct(TaskExecutionInterface $execution)
    {
        $this->execution = $execution;
    }

    /**
     * Get task execution instance
     * @return TaskExecutionInterface
     */
    public function getExecution(): TaskExecutionInterface
    {
        return $this->execution;
    }

    /**
     * Set task execution instance
     * @param TaskExecutionInterface $execution
     * @return $this
     */
    public function setExecution(TaskExecutionInterface $execution): TaskEntity
    {
        $this->execution = $execution;
        return $this;
    }

    /**
     * Get task execution state
     * @return int
     */
    public function getExecutionState(): int
    {
        return $this->getExecution()->refresh()->getState();
    }

    /**
     * Set task execution state
     * @param int $state
     * @return $this
     */
    public function setExecutionState(int $state): TaskEntity
    {
        $execution = $this->getExecution();
        $taskIdentifier = $execution->getTaskUuid();
        $pipelineIdentifier = $execution->getPipelineUuid();
        $executionIdentifier = $execution->getPipelineExecutionUuid();

        try {
            $repository = app()->make(TaskExecutionRepositoryInterface::class);
            $repository->update($taskIdentifier, $pipelineIdentifier, $executionIdentifier, $state);
        } catch (Throwable) {
            $model = TaskExecution::where('task_uuid', '=', $taskIdentifier)
                ->where('pipeline_uuid', '=', $pipelineIdentifier)
                ->where('pipeline_execution_uuid', '=', $executionIdentifier)
                ->first();
            $model->setState($state);
            $model->save();
        }

        return $this;
    }

    /**
     * Get task instance
     * @return TaskInterface
     */
    public function getTask(): TaskInterface
    {
        return $this->task;
    }

    /**
     * Indicates whether task should fail on error
     * @return bool
     */
    public function shouldFailOnError(): bool
    {
        return $this->getTask()->isFailingOnError();
    }

    /**
     * Set task instance
     * @param TaskInterface $task
     * @return $this
     */
    public function setTask(TaskInterface $task): TaskEntity
    {
        $this->task = $task;
        return $this;
    }

    /**
     * Add new server for task
     * @param ServerEntity $server
     * @return $this
     */
    public function addServer(ServerEntity $server): TaskEntity
    {
        $this->servers->add($server);
        return $this;
    }

    /**
     * Retrieve server instance
     * @param string $identifier
     * @return ServerEntity
     */
    public function findServer(string $identifier): ServerEntity
    {
        return $this->getServers()->filter(function (ServerEntity $server) use ($identifier): bool {
            return $server->getIdentifier() === $identifier;
        })->first();
    }

    /**
     * Check whether task already has specified server
     * @param string $identifier
     * @return bool
     */
    public function hasServer(string $identifier): bool
    {
        return $this->getServers()->filter(function (ServerEntity $server) use ($identifier): bool {
            return $server->getIdentifier() === $identifier;
        })->count() > 0;
    }

    /**
     * Get list of servers for this task
     * @return Collection|ServerEntity[]|array
     */
    public function getServers(): Collection
    {
        return $this->servers;
    }

    /**
     * Check whether task has incomplete actions
     * @return bool
     */
    public function hasIncompleteActions(): bool
    {
        $has = false;

        foreach ($this->getServers() as $server) {
            $has = $server->hasIncompleteActions();
            if ($has) {
                break;
            }
        }

        return $has;
    }

    /**
     * Check whether task has failed tasks
     * @return bool
     */
    public function hasFailedActions(): bool
    {
        $has = false;

        foreach ($this->getServers() as $server) {
            $has = $server->hasFailedActions();
            if ($has) {
                break;
            }
        }

        return $has;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'execution'     =>  $this->getExecution()->toArray(),
            'task'          =>  $this->getTask()->toArray(),
            'servers'       =>  $this->getServers()->toArray(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function bootstrap(): void
    {
        $this->task = $this->resolveTaskInstance();
        $this->servers = new Collection();
    }

    public function runHandler(): void
    {
        foreach ($this->getServers() as $server) {
            $server->runHandler();
        }
    }

    /**
     * Resolve task instance
     * @return TaskInterface
     */
    private function resolveTaskInstance(): TaskInterface
    {
        $identifier = $this->execution->getTaskUuid();

        try {
            $repository = app()->make(TaskRepositoryInterface::class);
            $instance = $repository->findBy('uuid', $identifier);
        } catch (Throwable) {
            $instance = Task::uuid($identifier);
        }

        return $instance;
    }
}
