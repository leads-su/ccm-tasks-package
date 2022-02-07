<?php

namespace ConsulConfigManager\Tasks\Services\TaskRunner\Entities;

use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use React\EventLoop\LoopInterface;
use React\EventLoop\TimerInterface;
use Illuminate\Contracts\Support\Arrayable;
use ConsulConfigManager\Tasks\Models\Pipeline;
use ConsulConfigManager\Tasks\Enums\ExecutionState;
use ConsulConfigManager\Tasks\Models\TaskExecution;
use ConsulConfigManager\Tasks\Models\PipelineExecution;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionInterface;
use ConsulConfigManager\Tasks\Services\TaskRunner\LoggableClass;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionRepositoryInterface;

/**
 * Class PipelineEntity
 * @package ConsulConfigManager\Tasks\Services\TaskRunner\Entities
 */
class PipelineEntity extends LoggableClass implements Arrayable
{
    /**
     * Pipeline Execution instance
     * @var PipelineExecutionInterface
     */
    private PipelineExecutionInterface $execution;

    /**
     * Pipeline instance
     * @var PipelineInterface
     */
    private PipelineInterface $pipeline;

    /**
     * Collection of pipeline tasks
     * @var Collection|TaskEntity[]|array
     */
    private Collection $tasks;

    /**
     * Event Loop instance
     * @var LoopInterface|null
     */
    private ?LoopInterface $loop = null;

    /**
     * Event Loop timer instance
     * @var TimerInterface|null
     */
    private ?TimerInterface $loopTimer = null;

    /**
     * Task we are currently working on
     * @var TaskEntity|null
     */
    private ?TaskEntity $currentTask = null;

    /**
     * PipelineEntity constructor.
     * @param PipelineExecutionInterface $execution
     * @return void
     */
    public function __construct(PipelineExecutionInterface $execution)
    {
        $this->execution = $execution;
    }

    /**
     * Get pipeline execution instance
     * @return PipelineExecutionInterface
     */
    public function getExecution(): PipelineExecutionInterface
    {
        return $this->execution;
    }

    /**
     * Set pipeline execution instance
     * @param PipelineExecutionInterface $execution
     * @return $this
     */
    public function setExecution(PipelineExecutionInterface $execution): PipelineEntity
    {
        $this->execution = $execution;
        return $this;
    }

    /**
     * Get pipeline execution state
     * @return int
     */
    public function getExecutionState(): int
    {
        return $this->getExecution()->refresh()->getState();
    }

    /**
     * Set pipeline execution state
     * @param int $state
     * @return $this
     */
    public function setExecutionState(int $state): PipelineEntity
    {
        $identifier = $this->getExecution()->getUuid();

        try {
            $repository = app()->make(PipelineExecutionRepositoryInterface::class);
            $repository->update($identifier, $state);
        } catch (Throwable) {
            $model = PipelineExecution::uuid($identifier);
            $model->setState($state);
            $model->save();
        }

        return $this;
    }

    /**
     * Get pipeline instance
     * @return PipelineInterface
     */
    public function getPipeline(): PipelineInterface
    {
        return $this->pipeline;
    }

    /**
     * Set pipeline instance
     * @param PipelineInterface $pipeline
     * @return $this
     */
    public function setPipeline(PipelineInterface $pipeline): PipelineEntity
    {
        $this->pipeline = $pipeline;
        return $this;
    }

    /**
     * Add new task to pipeline
     * @param TaskEntity $task
     * @return $this
     */
    public function addTask(TaskEntity $task): PipelineEntity
    {
        $this->tasks->add($task);
        return $this;
    }

    /**
     * Get collection of tasks
     * @return Collection|TaskEntity[]|array
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    /**
     * Check whether pipeline has incomplete tasks
     * @return bool
     */
    public function hasIncompleteTasks(): bool
    {
        $completedStates = [
            ExecutionState::CANCELED,
            ExecutionState::SUCCESS,
            ExecutionState::FAILURE,
            ExecutionState::PARTIALLY_COMPLETED,
        ];
        $has = false;

        foreach ($this->getTasks() as $action) {
            $executionState = $action->getExecution()->getState();
            if (!in_array($executionState, $completedStates)) {
                $has = true;
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
            'pipeline'      =>  $this->getPipeline()->toArray(),
            'tasks'         =>  $this->getTasks()->toArray(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function bootstrap(): void
    {
        $this->pipeline = $this->resolvePipelineInstance();
        $this->tasks = new Collection();
    }

    /**
     * Run pipeline handler
     * @param LoopInterface $loop
     * @return void
     */
    public function runHandler(LoopInterface $loop): void
    {
        $this->debugWarn(sprintf(
            'Pipeline Identifier: %s | Pipeline Name: %s | Task Count: %d | Starting Execution',
            $this->execution->getPipelineUuid(),
            $this->pipeline->getName(),
            $this->tasks->count(),
        ));

        $this->loop = $loop;
        $this->setExecutionState(ExecutionState::EXECUTING);
        $this->loopTimer = $this->loop->addPeriodicTimer(1.0, function (): void {
            if (!$this->hasIncompleteTasks()) {
                $this->markPipelineFinished();
            }
            $this->selectNextTask();

            if ($this->currentTask !== null) {
                $taskExecutionState = $this->currentTask->getExecutionState();

                switch ($taskExecutionState) {
                    case ExecutionState::CREATED:
                        $this->debugLine(sprintf(
                            'Pipeline: %s | Task: %s | Starting processing first task `%s`',
                            $this->execution->getPipelineUuid(),
                            $this->currentTask->getExecution()->getTaskUuid(),
                            $this->currentTask->getTask()->getName(),
                        ));
                        $this->currentTask->setExecutionState(ExecutionState::EXECUTING);
                        $this->markOtherTasksAs(ExecutionState::WAITING);
                        break;
                    case ExecutionState::WAITING:
                        $this->debugLine(sprintf(
                            'Pipeline: %s | Task: %s | Starting processing next task `%s`',
                            $this->execution->getPipelineUuid(),
                            $this->currentTask->getExecution()->getTaskUuid(),
                            $this->currentTask->getTask()->getName(),
                        ));
                        $this->currentTask->setExecutionState(ExecutionState::EXECUTING);
                        break;
                    case ExecutionState::PARTIALLY_COMPLETED:
                        $this->debugWarn(sprintf(
                            'Pipeline: %s | Task: %s | Partially processed task `%s`',
                            $this->execution->getPipelineUuid(),
                            $this->currentTask->getExecution()->getTaskUuid(),
                            $this->currentTask->getTask()->getName(),
                        ));
                        break;
                }
                $this->currentTask->runHandler();
            }
        });
    }

    /**
     * Select next task in chain
     * @return void
     */
    public function selectNextTask(): void
    {
        if ($this->tasks->count() === 0) {
            $this->currentTask = null;
        }

        if ($this->currentTask === null) {
            $this->currentTask = $this->getTasks()->first();
        }

        if ($this->currentTask === null) {
            $this->loop->cancelTimer($this->loopTimer);
            return;
        }

        if (!$this->currentTask->hasIncompleteActions()) {
            if ($this->currentTask->hasFailedActions()) {
                $this->currentTask->setExecutionState(ExecutionState::PARTIALLY_COMPLETED);
                if ($this->currentTask->shouldFailOnError()) {
                    $this->markOtherTasksAs(ExecutionState::CANCELED, true);
                    $this->tasks = new Collection();
                }
            } else {
                $this->tasks->forget(0);
                $this->tasks = $this->tasks->values();
                $this->currentTask->setExecutionState(ExecutionState::SUCCESS);
                $this->currentTask = $this->getTasks()->first();
            }
        }
    }

    /**
     * Update other tasks and set their state to provided value
     * @param int $state
     * @param bool $applySameToActions
     * @return $this
     */
    public function markOtherTasksAs(int $state, bool $applySameToActions = false): PipelineEntity
    {
        $this->getTasks()->filter(function (TaskEntity $taskEntity): bool {
            return $taskEntity !== $this->currentTask;
        })->each(function (TaskEntity $taskEntity) use ($state, $applySameToActions): void {
            $taskEntity->setExecutionState($state);
            if ($applySameToActions) {
                $taskEntity->getServers()->each(function (ServerEntity $serverEntity) use ($state): void {
                    $serverEntity->getActions()->each(function (ActionEntity $actionEntity) use ($state): void {
                        $actionEntity->setExecutionState($state);
                    });
                });
            }
        });
        return $this;
    }

    /**
     * Resolve pipeline instance
     * @return PipelineInterface
     */
    private function resolvePipelineInstance(): PipelineInterface
    {
        $identifier = $this->execution->getPipelineUuid();

        try {
            $repository = app()->make(PipelineRepositoryInterface::class);
            $instance = $repository->findBy('uuid', $identifier);
        } catch (Throwable) {
            $instance = Pipeline::uuid($identifier);
        }

        return $instance;
    }

    /**
     * Mark pipeline finished once ready
     * @return void
     */
    private function markPipelineFinished(): void
    {
        $executionIdentifier = $this->getExecution()->getUuid();
        $states = [];

        try {
            $repository = app()->make(TaskExecutionRepositoryInterface::class);
            $tasks = $repository->findManyBy('pipeline_execution_uuid', $executionIdentifier);
            /**
             * @var TaskExecutionInterface $task
             */
            foreach ($tasks as $task) {
                $states[] = $task->getState();
            }
        } catch (Throwable) {
            $tasks = TaskExecution::where('pipeline_execution_uuid', '=', $executionIdentifier)->get();
            /**
             * @var TaskExecutionInterface $task
             */
            foreach ($tasks as $task) {
                $states[] = $task->getState();
            }
        }
        $flippedStates = array_flip($states);
        if (count($flippedStates) === 1) {
            $pipelineState = Arr::first(array_keys($flippedStates));
        } else {
            $pipelineState = ExecutionState::PARTIALLY_COMPLETED;
        }
        $this->setExecutionState($pipelineState);
    }
}
