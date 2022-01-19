<?php

namespace ConsulConfigManager\Tasks\Services\TaskRunner;

use Throwable;
use React\EventLoop\Loop;
use Illuminate\Support\Arr;
use React\EventLoop\LoopInterface;
use ConsulConfigManager\Tasks\Enums\ExecutionState;
use ConsulConfigManager\Tasks\Models\ActionExecutionLog;
use Illuminate\Contracts\Container\BindingResolutionException;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionInterface;
use ConsulConfigManager\Tasks\Services\TaskRunner\Manager\RemoteTask;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionRepositoryInterface;
use ConsulConfigManager\Tasks\Services\TaskRunner\Exceptions\SequenceNotProvidedException;

/**
 * Class EventLoop
 * @package ConsulConfigManager\Tasks\Services\TaskRunner
 */
class EventLoop
{
    /**
     * Event Loop instance
     * @var LoopInterface
     */
    private LoopInterface $loop;

    /**
     * Pipeline Execution repository instance
     * @var PipelineExecutionRepositoryInterface
     */
    private PipelineExecutionRepositoryInterface $pipelineExecutionRepository;

    /**
     * Task Execution repository instance
     * @var TaskExecutionRepositoryInterface
     */
    private TaskExecutionRepositoryInterface $taskExecutionRepository;

    /**
     * Action Execution repository instance
     * @var ActionExecutionRepositoryInterface
     */
    private ActionExecutionRepositoryInterface $actionExecutionRepository;

    /**
     * Loop execution sequence
     * @var array
     */
    private array $sequence = [];

    /**
     * List of timers for tasks on a specific server
     * @var array
     */
    private array $serversTaskTimers = [];

    /**
     * Task which is currently running on given server
     * @var array
     */
    private array $currentTask = [];

    /**
     * Action which is currently running on given server
     * @var array
     */
    private array $currentAction = [];

    /**
     * Task State reference array
     * @var array
     */
    private array $taskStateReference = [];

    /**
     * Action State reference array
     * @var array
     */
    private array $actionStateReference = [];

    /**
     * EventLoop constructor.
     * @return void
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->boot();
    }

    /**
     * Add sequence information
     * @param array $sequence
     * @return $this
     */
    public function withSequence(array $sequence): EventLoop
    {
        $this->sequence = $sequence;
        return $this;
    }

    /**
     * Start event loop
     * @return void
     */
    public function start(): void
    {
        if (empty($this->sequence)) {
            throw new SequenceNotProvidedException();
        }

        foreach ($this->getServersList() as $serverIdentifier => $serverData) {
            $serverTasks = $this->getTasksList($serverIdentifier);
            $this->currentTask[$serverIdentifier] = Arr::first(array_keys($serverTasks));

            foreach ($serverTasks as $taskIdentifier => $taskData) {
                $this->taskStateReference[$serverIdentifier][$taskIdentifier] = 0;
                $taskActions = $this->getActionsList($taskData);
                $this->currentAction[$serverIdentifier] = Arr::first(array_keys($taskActions));

                foreach ($taskActions as $actionIdentifier => $actionData) {
                    $this->actionStateReference[$serverIdentifier][$taskIdentifier][$actionIdentifier] = 0;
                }
            }

            $this->serversTaskTimers[$serverIdentifier] = $this->loop->addPeriodicTimer(1.0, function () use ($serverIdentifier): void {
                $this->handleServerExecution($serverIdentifier);
                try {
                    $this->handleServerExecution($serverIdentifier);
                } catch (Throwable) {
                    $this->setPipelineExecutionState(ExecutionState::PARTIALLY_COMPLETED)
                        ->loop
                        ->cancelTimer($this->serversTaskTimers[$serverIdentifier]);
                }
            });
        }
        $this->loop->run();
    }

    /**
     * Boot class
     * @return void
     * @throws BindingResolutionException
     */
    protected function boot(): void
    {
        $this->loop = Loop::get();
        $this->pipelineExecutionRepository = app()->make(PipelineExecutionRepositoryInterface::class);
        $this->taskExecutionRepository = app()->make(TaskExecutionRepositoryInterface::class);
        $this->actionExecutionRepository = app()->make(ActionExecutionRepositoryInterface::class);
    }

    /**
     * Handle pipeline execution on provided server
     * @param string $serverIdentifier
     * @return void
     */
    private function handleServerExecution(string $serverIdentifier): void
    {
        $currentTask = $this->getCurrentTask($serverIdentifier);

        $this->handleTasksProcessing($serverIdentifier, $currentTask);
        $this->handleActionsProcessing($serverIdentifier, $currentTask);
    }

    /**
     * Handle tasks execution on provided server
     * @param string $serverIdentifier
     * @param string $currentTask
     * @return void
     */
    private function handleTasksProcessing(string $serverIdentifier, string $currentTask): void
    {
        $currentTaskState = $this->getTaskState($serverIdentifier);

        if ($this->isTaskCompleted($currentTask)) {
            if (!$this->hasIncompleteTasks($serverIdentifier)) {
                $this->setPipelineExecutionState($this->generatePipelineStateBasedOnTasksStates($serverIdentifier));
                $this->loop->cancelTimer($this->serversTaskTimers[$serverIdentifier]);
            }
        }

        switch ($currentTaskState) {
            case ExecutionState::CREATED:
                $this->setTaskState(
                    serverIdentifier: $serverIdentifier,
                    state: ExecutionState::EXECUTING,
                    taskIdentifier: $currentTask,
                )->markOtherTasksAs($serverIdentifier, ExecutionState::WAITING);
                break;
            case ExecutionState::WAITING:
                if ($this->hasFailedTasks($serverIdentifier)) {
                    $this->markOtherTasksAs($serverIdentifier, ExecutionState::CANCELED);
                } else {
                    $this->setCurrentAction(
                        $serverIdentifier,
                        Arr::first(array_keys(
                            $this->actionStateReference[$serverIdentifier][$currentTask]
                        ))
                    )->setTaskState(
                        serverIdentifier: $serverIdentifier,
                        state: ExecutionState::EXECUTING,
                        taskIdentifier: $currentTask,
                    );
                }
                break;
            default:
        }
    }

    /**
     * Handle actions execution on provided server for specified task
     * @param string $serverIdentifier
     * @param string $currentTask
     * @return void
     */
    private function handleActionsProcessing(string $serverIdentifier, string $currentTask): void
    {
        $currentTaskState = $this->getTaskState($serverIdentifier);
        if ($currentTaskState !== ExecutionState::EXECUTING) {
            return;
        }

        $currentAction = $this->getCurrentAction($serverIdentifier);
        $currentActionState = $this->getActionState($serverIdentifier, $currentTask, $currentAction);

        if ($this->isActionCompleted($serverIdentifier, $currentTask, $currentAction)) {
            if (!$this->hasIncompleteActions($serverIdentifier, $currentTask)) {
                $this->setTaskState(
                    $serverIdentifier,
                    $this->generateTaskStateBasedOnActionsStates($serverIdentifier, $currentTask),
                    $currentTask,
                );
            }
        }

        switch ($currentActionState) {
            case ExecutionState::CREATED:
                $this->setActionState(
                    serverIdentifier: $serverIdentifier,
                    taskIdentifier: $currentTask,
                    state: ExecutionState::EXECUTING,
                    actionIdentifier: $currentAction,
                )->markOtherActionsAs($serverIdentifier, $currentTask, ExecutionState::WAITING);
                $this->runAction(
                    serverIdentifier: $serverIdentifier,
                    taskIdentifier: $currentTask,
                    actionIdentifier: $currentAction,
                );
                break;
            case ExecutionState::WAITING:
                if ($this->hasFailedActions($serverIdentifier, $currentTask)) {
                    $this->markOtherActionsAs($serverIdentifier, $currentTask, ExecutionState::CANCELED);
                } else {
                    $this->setActionState(
                        serverIdentifier: $serverIdentifier,
                        taskIdentifier: $currentTask,
                        state: ExecutionState::EXECUTING,
                        actionIdentifier: $currentAction,
                    );
                    $this->runAction(
                        serverIdentifier: $serverIdentifier,
                        taskIdentifier: $currentTask,
                        actionIdentifier: $currentAction,
                    );
                }
                break;
            default:
        }
    }

    /**
     * Start action runner
     * @param string $serverIdentifier
     * @param string $taskIdentifier
     * @param string $actionIdentifier
     * @return void
     */
    private function runAction(string $serverIdentifier, string $taskIdentifier, string $actionIdentifier): void
    {
        $this->getActionRunner(
            serverIdentifier: $serverIdentifier,
            taskIdentifier: $taskIdentifier,
            actionIdentifier: $actionIdentifier,
        )->execute(function (int $statusCode, array $output = []) use ($serverIdentifier, $taskIdentifier, $actionIdentifier): void {
            $stateCode = $statusCode === 0 ? ExecutionState::SUCCESS : ExecutionState::FAILURE;
            $this
                ->setActionExecutionLog(
                    $serverIdentifier,
                    $taskIdentifier,
                    $statusCode,
                    $output,
                    $actionIdentifier,
                )
                ->setActionState($serverIdentifier, $taskIdentifier, $stateCode, $actionIdentifier);
        });
    }

    /**
     * Get list of target servers
     * @return array
     */
    private function getServersList(): array
    {
        return Arr::get($this->sequence, 'servers');
    }

    /**
     * Get list of tasks for a given server
     * @param string $serverIdentifier
     * @return array
     */
    private function getTasksList(string $serverIdentifier): array
    {
        return Arr::get($this->sequence, sprintf('servers.%s.tasks', $serverIdentifier));
    }

    /**
     * Get list of actions for specified task
     * @param array $taskData
     * @return array
     */
    private function getActionsList(array $taskData): array
    {
        return Arr::get($taskData, 'actions');
    }

    /**
     * Get Pipeline Execution instance
     * @return PipelineExecutionInterface
     */
    private function getPipelineExecution(): PipelineExecutionInterface
    {
        return Arr::get($this->sequence, 'execution');
    }

    /**
     * Set pipeline execution state to specified value
     * @param int $state
     * @return EventLoop
     */
    private function setPipelineExecutionState(int $state): EventLoop
    {
        $execution = $this->getPipelineExecution();
        $this->pipelineExecutionRepository->update($execution->getUuid(), $state);
        return $this;
    }

    /**
     * Get current task for specified server
     * @param string $serverIdentifier
     * @return string
     */
    private function getCurrentTask(string $serverIdentifier): string
    {
        return $this->currentTask[$serverIdentifier];
    }

    /**
     * Set current task for specified server
     * @param string $serverIdentifier
     * @param string $taskIdentifier
     * @return EventLoop
     */
    private function setCurrentTask(string $serverIdentifier, string $taskIdentifier): EventLoop
    {
        $this->currentTask[$serverIdentifier] = $taskIdentifier;
        return $this;
    }

    /**
     * Get state for specified task
     * @param string $serverIdentifier
     * @param string|null $taskIdentifier
     * @return int
     */
    private function getTaskState(string $serverIdentifier, ?string $taskIdentifier = null): int
    {
        $taskIdentifier = $taskIdentifier ?? $this->getCurrentTask($serverIdentifier);
        return $this->taskStateReference[$serverIdentifier][$taskIdentifier];
    }

    /**
     * Get Task Execution instance
     * @param string $serverIdentifier
     * @param string|null $taskIdentifier
     * @return TaskExecutionInterface
     */
    private function getTaskExecution(string $serverIdentifier, ?string $taskIdentifier = null): TaskExecutionInterface
    {
        $taskIdentifier = $taskIdentifier ?? $this->getCurrentTask($serverIdentifier);
        $key = sprintf('servers.%s.tasks.%s.execution', $serverIdentifier, $taskIdentifier);
        return Arr::get($this->sequence, $key);
    }

    /**
     * Set Task Execution state
     * @param string $serverIdentifier
     * @param int $state
     * @param string|null $taskIdentifier
     * @return EventLoop
     */
    private function setTaskExecutionState(string $serverIdentifier, int $state, ?string $taskIdentifier = null): EventLoop
    {
        $taskIdentifier = $taskIdentifier ?? $this->getCurrentTask($serverIdentifier);
        $execution = $this->getTaskExecution($serverIdentifier, $taskIdentifier);
        $this->taskExecutionRepository->updateById($execution->getID(), $state);
        return $this;
    }

    /**
     * Set state for specified task
     * @param string $serverIdentifier
     * @param int $state
     * @param string|null $taskIdentifier
     * @return EventLoop
     */
    private function setTaskState(string $serverIdentifier, int $state, ?string $taskIdentifier = null): EventLoop
    {
        $taskIdentifier = $taskIdentifier ?? $this->getCurrentTask($serverIdentifier);
        $this->setTaskExecutionState($serverIdentifier, $state, $taskIdentifier);
        $this->taskStateReference[$serverIdentifier][$taskIdentifier] = $state;
        return $this;
    }

    /**
     * Check whether task is completed across all servers
     * @param string $taskIdentifier
     * @return bool
     */
    private function isTaskCompleted(string $taskIdentifier): bool
    {
        $serversWithTask = 0;
        $serversCompleted = 0;
        foreach ($this->taskStateReference as $serverTasks) {
            foreach ($serverTasks as $localTaskIdentifier => $state) {
                if ($localTaskIdentifier === $taskIdentifier) {
                    $serversWithTask++;
                    if (
                        $state === ExecutionState::SUCCESS ||
                        $state === ExecutionState::FAILURE ||
                        $state === ExecutionState::PARTIALLY_COMPLETED ||
                        $state === ExecutionState::CANCELED
                    ) {
                        $serversCompleted++;
                    }
                }
            }
        }
        return $serversWithTask === $serversCompleted;
    }

    /**
     * Check whether there are any incomplete tasks left
     * @param string $serverIdentifier
     * @return bool
     */
    private function hasIncompleteTasks(string $serverIdentifier): bool
    {
        $has = false;
        foreach ($this->taskStateReference[$serverIdentifier] as $identifier => $state) {
            if (
                $state !== ExecutionState::SUCCESS &&
                $state !== ExecutionState::FAILURE &&
                $state !== ExecutionState::CANCELED
            ) {
                $this->setCurrentTask($serverIdentifier, $identifier);
                $has = true;
                break;
            }
        }
        return $has;
    }

    /**
     * Check whether pipeline has any failed tasks
     * @param string $serverIdentifier
     * @return bool
     */
    private function hasFailedTasks(string $serverIdentifier): bool
    {
        $has = false;
        foreach ($this->taskStateReference[$serverIdentifier] as $identifier => $state) {
            if ($state === ExecutionState::FAILURE) {
                $has = true;
                break;
            }
        }
        return $has;
    }

    /**
     * Set new status for all tasks which are not completed
     * @param string $serverIdentifier
     * @param int $state
     * @return EventLoop
     */
    private function markOtherTasksAs(string $serverIdentifier, int $state): EventLoop
    {
        foreach ($this->taskStateReference[$serverIdentifier] as $identifier => $oldState) {
            if (
                $oldState !== ExecutionState::SUCCESS &&
                $oldState !== ExecutionState::FAILURE &&
                $identifier !== $this->getCurrentTask($serverIdentifier)
            ) {
                $this->setTaskState(
                    serverIdentifier: $serverIdentifier,
                    state: $state,
                    taskIdentifier: $identifier,
                );
            }
        }
        return $this;
    }

    /**
     * Get current action for specified server
     * @param string $serverIdentifier
     * @return string
     */
    private function getCurrentAction(string $serverIdentifier): string
    {
        return $this->currentAction[$serverIdentifier];
    }

    /**
     * Set current action for specified server
     * @param string $serverInterface
     * @param string $actionIdentifier
     * @return EventLoop
     */
    private function setCurrentAction(string $serverInterface, string $actionIdentifier): EventLoop
    {
        $this->currentAction[$serverInterface] = $actionIdentifier;
        return $this;
    }

    /**
     * Get Action Execution instance
     * @param string $serverIdentifier
     * @param string $taskIdentifier
     * @param string|null $actionIdentifier
     * @return ActionExecutionInterface
     */
    private function getActionExecution(string $serverIdentifier, string $taskIdentifier, ?string $actionIdentifier = null): ActionExecutionInterface
    {
        $actionIdentifier = $actionIdentifier ?? $this->getCurrentAction($actionIdentifier);
        $key = sprintf('servers.%s.tasks.%s.actions.%s.execution', $serverIdentifier, $taskIdentifier, $actionIdentifier);
        return Arr::get($this->sequence, $key);
    }

    /**
     * Set Action Execution state
     * @param string $serverIdentifier
     * @param string $taskIdentifier
     * @param int $state
     * @param string|null $actionIdentifier
     * @return EventLoop
     */
    private function setActionExecutionState(string $serverIdentifier, string $taskIdentifier, int $state, ?string $actionIdentifier = null): EventLoop
    {
        $actionIdentifier = $actionIdentifier ?? $this->getCurrentAction($actionIdentifier);
        $execution = $this->getActionExecution($serverIdentifier, $taskIdentifier, $actionIdentifier);
        $this->actionExecutionRepository->updateById($execution->getID(), $state);
        return $this;
    }

    /**
     * Set action execution log information
     * @param string $serverIdentifier
     * @param string $taskIdentifier
     * @param int $exitCode
     * @param array $output
     * @param string|null $actionIdentifier
     * @return EventLoop
     */
    private function setActionExecutionLog(string $serverIdentifier, string $taskIdentifier, int $exitCode, array $output, ?string $actionIdentifier = null): EventLoop
    {
        $actionIdentifier = $actionIdentifier ?? $this->getCurrentAction($actionIdentifier);
        $execution = $this->getActionExecution($serverIdentifier, $taskIdentifier, $actionIdentifier);

        $log = new ActionExecutionLog();
        $log->setActionExecutionID($execution->getID());
        $log->setExitCode($exitCode);
        $log->setOutput($output);
        $log->save();

        return $this;
    }

    /**
     * Get Action runner
     * @param string $serverIdentifier
     * @param string $taskIdentifier
     * @param string|null $actionIdentifier
     * @return RemoteTask
     */
    private function getActionRunner(string $serverIdentifier, string $taskIdentifier, ?string $actionIdentifier = null): RemoteTask
    {
        $actionIdentifier = $actionIdentifier ?? $this->getCurrentAction($actionIdentifier);
        $key = sprintf('servers.%s.tasks.%s.actions.%s.runner', $serverIdentifier, $taskIdentifier, $actionIdentifier);
        return Arr::get($this->sequence, $key);
    }

    /**
     * Get state for specified action
     * @param string $serverIdentifier
     * @param string $taskIdentifier
     * @param string|null $actionIdentifier
     * @return int
     */
    private function getActionState(string $serverIdentifier, string $taskIdentifier, ?string $actionIdentifier = null): int
    {
        $actionIdentifier = $actionIdentifier ?? $this->getCurrentAction($serverIdentifier);
        return $this->actionStateReference[$serverIdentifier][$taskIdentifier][$actionIdentifier];
    }

    /**
     * Set state for specified action
     * @param string $serverIdentifier
     * @param string $taskIdentifier
     * @param int $state
     * @param string|null $actionIdentifier
     * @return EventLoop
     */
    private function setActionState(string $serverIdentifier, string $taskIdentifier, int $state, ?string $actionIdentifier = null): EventLoop
    {
        $actionIdentifier = $actionIdentifier ?? $this->getCurrentAction($serverIdentifier);
        $this->setActionExecutionState($serverIdentifier, $taskIdentifier, $state, $actionIdentifier);
        $this->actionStateReference[$serverIdentifier][$taskIdentifier][$actionIdentifier] = $state;
        if ($state === ExecutionState::FAILURE) {
            $this->markOtherActionsAs($serverIdentifier, $taskIdentifier, ExecutionState::CANCELED);
        }
        return $this;
    }

    /**
     * Check whether specified action is completed
     * @param string $serverIdentifier
     * @param string $taskIdentifier
     * @param string|null $actionIdentifier
     * @return bool
     */
    private function isActionCompleted(string $serverIdentifier, string $taskIdentifier, ?string $actionIdentifier = null): bool
    {
        $actionIdentifier = $actionIdentifier ?? $this->getCurrentAction($serverIdentifier);
        $state = $this->actionStateReference[$serverIdentifier][$taskIdentifier][$actionIdentifier];
        return $state === ExecutionState::SUCCESS ||
            $state === ExecutionState::FAILURE ||
            $state === ExecutionState::CANCELED;
    }

    /**
     * Check whether there are any incomplete actions left
     * @param string $serverIdentifier
     * @param string $taskIdentifier
     * @return bool
     */
    private function hasIncompleteActions(string $serverIdentifier, string $taskIdentifier): bool
    {
        $has = false;
        foreach ($this->actionStateReference[$serverIdentifier][$taskIdentifier] as $identifier => $state) {
            if (
                $state !== ExecutionState::SUCCESS &&
                $state !== ExecutionState::FAILURE &&
                $state !== ExecutionState::CANCELED
            ) {
                $this->setCurrentAction($serverIdentifier, $identifier);
                $has = true;
                break;
            }
        }
        return $has;
    }

    /**
     * Check whether task has any failed actions
     * @param string $serverIdentifier
     * @param string $taskIdentifier
     * @return bool
     */
    private function hasFailedActions(string $serverIdentifier, string $taskIdentifier): bool
    {
        $has = false;
        foreach ($this->actionStateReference[$serverIdentifier][$taskIdentifier] as $identifier => $state) {
            if ($state === ExecutionState::FAILURE) {
                $has = true;
                break;
            }
        }
        return $has;
    }

    /**
     * Set new status for all actions which are not completed
     * @param string $serverIdentifier
     * @param string $taskIdentifier
     * @param int $state
     * @return EventLoop
     */
    private function markOtherActionsAs(string $serverIdentifier, string $taskIdentifier, int $state): EventLoop
    {
        foreach ($this->actionStateReference[$serverIdentifier][$taskIdentifier] as $identifier => $oldState) {
            if (
                $oldState !== ExecutionState::SUCCESS &&
                $oldState !== ExecutionState::FAILURE &&
                $identifier !== $this->getCurrentAction($serverIdentifier)
            ) {
                $this->setActionState(
                    serverIdentifier: $serverIdentifier,
                    taskIdentifier: $taskIdentifier,
                    state: $state,
                    actionIdentifier: $identifier,
                );
            }
        }
        return $this;
    }

    /**
     * Generate task state based on states of actions in this task
     * @param string $serverIdentifier
     * @param string $taskIdentifier
     * @return int
     */
    private function generateTaskStateBasedOnActionsStates(string $serverIdentifier, string $taskIdentifier): int
    {
        $states = $this->actionStateReference[$serverIdentifier][$taskIdentifier];
        return $this->deductStateFromStates($states);
    }

    /**
     * Generate pipeline state based on states of tasks in this pipeline
     * @param string $serverIdentifier
     * @return int
     */
    private function generatePipelineStateBasedOnTasksStates(string $serverIdentifier): int
    {
        $states = $this->taskStateReference[$serverIdentifier];
        return $this->deductStateFromStates($states);
    }

    /**
     * Deduct state value from provided list of states
     * @param array $states
     * @return int
     */
    private function deductStateFromStates(array $states): int
    {
        $flippedStates = array_flip($states);
        if (count($flippedStates) === 1) {
            return Arr::first(array_keys($flippedStates));
        }
        return ExecutionState::PARTIALLY_COMPLETED;
    }
}
