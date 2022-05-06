<?php

namespace ConsulConfigManager\Tasks\Services\TaskRunner\Entities;

use Throwable;
use Illuminate\Contracts\Support\Arrayable;
use ConsulConfigManager\Tasks\Models\Action;
use ConsulConfigManager\Tasks\Enums\ExecutionState;
use ConsulConfigManager\Tasks\Models\TaskExecution;
use ConsulConfigManager\Tasks\Models\ActionExecutionLog;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\Services\TaskRunner\LoggableClass;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;
use ConsulConfigManager\Tasks\Services\TaskRunner\Actions\AbstractAction;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionRepositoryInterface;

/**
 * Class ActionEntity
 * @package ConsulConfigManager\Tasks\Services\TaskRunner\Entities
 */
class ActionEntity extends LoggableClass implements Arrayable
{
    /**
     * Action Execution instance
     * @var ActionExecutionInterface
     */
    private ActionExecutionInterface $execution;

    /**
     * Action instance
     * @var ActionInterface
     */
    private ActionInterface $action;

    /**
     * Action runner instance
     * @var AbstractAction
     */
    private AbstractAction $runner;

    /**
     * ActionEntity constructor.
     * @param ActionExecutionInterface $execution
     * @param AbstractAction $runner
     * @return void
     */
    public function __construct(ActionExecutionInterface $execution, AbstractAction $runner)
    {
        $this->execution = $execution;
        $this->runner = $runner;
    }

    /**
     * Get action execution instance
     * @return ActionExecutionInterface
     */
    public function getExecution(): ActionExecutionInterface
    {
        return $this->execution;
    }

    /**
     * Set action execution instance
     * @param ActionExecutionInterface $execution
     * @return $this
     */
    public function setExecution(ActionExecutionInterface $execution): ActionEntity
    {
        $this->execution = $execution;
        return $this;
    }

    /**
     * Get action execution state
     * @return int
     */
    public function getExecutionState(): int
    {
        return $this->getExecution()->refresh()->getState();
    }

    /**
     * Set action execution state
     * @param int $state
     * @param array $ignoredStates
     * @return $this
     */
    public function setExecutionState(int $state, array $ignoredStates = []): ActionEntity
    {
        $execution = $this->getExecution();
        $executionState = $this->getExecutionState();

        $skipUpdateFor = array_merge([
            ExecutionState::CANCELED,
            ExecutionState::SUCCESS,
            ExecutionState::FAILURE,
        ], $ignoredStates);
        if (in_array($executionState, $skipUpdateFor)) {
            return $this;
        }

        $serverIdentifier = $execution->getServerUuid();
        $actionIdentifier = $execution->getActionUuid();
        $taskIdentifier = $execution->getTaskUuid();
        $pipelineIdentifier = $execution->getPipelineUuid();
        $executionIdentifier = $execution->getPipelineExecutionUuid();

        try {
            $repository = app()->make(ActionExecutionRepositoryInterface::class);
            $repository->update($serverIdentifier, $actionIdentifier, $taskIdentifier, $pipelineIdentifier, $executionIdentifier, $state);
            // @codeCoverageIgnoreStart
        } catch (Throwable) {
            $model = TaskExecution::where('server_uuid', '=', $serverIdentifier)
                ->where('action_uuid', '=', $actionIdentifier)
                ->where('task_uuid', '=', $taskIdentifier)
                ->where('pipeline_uuid', '=', $pipelineIdentifier)
                ->where('pipeline_execution_uuid', '=', $executionIdentifier)
                ->first();
            $model->setState($state);
            $model->save();
        }
        // @codeCoverageIgnoreEnd

        return $this;
    }

    /**
     * Set action execution log information
     * @param int $exitCode
     * @param array $output
     * @return $this
     */
    public function setExecutionLog(int $exitCode, array $output): ActionEntity
    {
        $executionIdentifier = $this->getExecution()->getID();

        $log = new ActionExecutionLog();
        $log->setActionExecutionID($executionIdentifier);
        $log->setExitCode($exitCode);
        $log->setOutput($output);
        $log->save();

        return $this;
    }

    /**
     * Get action instance
     * @return ActionInterface
     */
    public function getAction(): ActionInterface
    {
        return $this->action;
    }

    /**
     * Set action instance
     * @param ActionInterface $action
     * @return $this
     */
    public function setAction(ActionInterface $action): ActionEntity
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Get action runner instance
     * @return AbstractAction
     */
    public function getRunner(): AbstractAction
    {
        return $this->runner;
    }

    /**
     * Set action runner instance
     * @param AbstractAction $runner
     * @return $this
     */
    public function setRunner(AbstractAction $runner): ActionEntity
    {
        $this->runner = $runner;
        return $this;
    }

    /**
     * Indicates whether action should fail on error
     * @return bool
     */
    public function shouldFailOnError(): bool
    {
        return $this->getAction()->isFailingOnError();
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'execution'         =>  $this->getExecution()->toArray(),
            'action'            =>  $this->getAction()->toArray(),
            'runner'            =>  $this->getRunner()->toActionArray(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function bootstrap(): void
    {
        $this->action = $this->resolveActionInstance();
    }

    /**
     * Resolve action instance
     * @return ActionInterface
     */
    private function resolveActionInstance(): ActionInterface
    {
        $identifier = $this->execution->getActionUuid();

        try {
            $repository = app()->make(ActionRepositoryInterface::class);
            $instance = $repository->findBy('uuid', $identifier);
            // @codeCoverageIgnoreStart
        } catch (Throwable) {
            $instance = Action::uuid($identifier);
        }
        // @codeCoverageIgnoreEnd

        return $instance;
    }
}
