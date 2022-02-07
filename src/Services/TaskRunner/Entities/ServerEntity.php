<?php

namespace ConsulConfigManager\Tasks\Services\TaskRunner\Entities;

use Throwable;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;
use ConsulConfigManager\Tasks\Enums\ExecutionState;
use ConsulConfigManager\Consul\Agent\Models\Service;
use ConsulConfigManager\Tasks\Services\TaskRunner\LoggableClass;
use ConsulConfigManager\Consul\Agent\Interfaces\ServiceInterface;
use ConsulConfigManager\Consul\Agent\Interfaces\ServiceRepositoryInterface;

/**
 * Class ServerEntity
 * @package ConsulConfigManager\Tasks\Services\TaskRunner\Entities
 */
class ServerEntity extends LoggableClass implements Arrayable
{
    /**
     * Server identifier reference
     * @var string
     */
    private string $identifier;

    /**
     * Server instance
     * @var ServiceInterface
     */
    private ServiceInterface $server;

    /**
     * Collection of actions for server
     * @var Collection|ActionEntity[]|array
     */
    private Collection $actions;

    /**
     * Action we are currently working on
     * @var ActionEntity|null
     */
    private ?ActionEntity $currentAction = null;


    /**
     * ServerEntity constructor.
     * @param string $identifier
     * @return void
     */
    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * Get server identifier
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Set server identifier
     * @param string $identifier
     * @return $this
     */
    public function setIdentifier(string $identifier): ServerEntity
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * Get server instance
     * @return ServiceInterface
     */
    public function getServer(): ServiceInterface
    {
        return $this->server;
    }

    /**
     * Set server instance
     * @param ServiceInterface $server
     * @return $this
     */
    public function setServer(ServiceInterface $server): ServerEntity
    {
        $this->server = $server;
        return $this;
    }

    /**
     * Get collection of actions for this server
     * @return Collection|ActionEntity[]|array
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    /**
     * Add new action to server
     * @param ActionEntity $action
     * @return $this
     */
    public function addAction(ActionEntity $action): ServerEntity
    {
        $this->actions->add($action);
        return $this;
    }

    /**
     * Check whether server has incomplete actions
     * @return bool
     */
    public function hasIncompleteActions(): bool
    {
        $completedStates = [
            ExecutionState::CANCELED,
            ExecutionState::SUCCESS,
            ExecutionState::FAILURE,
        ];
        $has = false;

        foreach ($this->getActions() as $action) {
            $executionState = $action->getExecutionState();
            if (!in_array($executionState, $completedStates)) {
                $has = true;
                break;
            }
        }

        return $has;
    }

    /**
     * Check if there are any failed actions
     * @return bool
     */
    public function hasFailedActions(): bool
    {
        $has = false;

        foreach ($this->getActions() as $action) {
            $executionState = $action->getExecutionState();
            if ($executionState === ExecutionState::FAILURE) {
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
            'identifier'        =>  $this->getIdentifier(),
            'server'            =>  $this->getServer()->toArray(),
            'actions'           =>  $this->getActions()->toArray(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function bootstrap(): void
    {
        $this->server = $this->resolveServerInstance();
        $this->actions = new Collection();
    }

    public function runHandler(): void
    {
        $this->selectNextAction();
        $actionExecutionState = $this->currentAction->getExecutionState();

        switch ($actionExecutionState) {
            case ExecutionState::CREATED:
                $this->debugLine(sprintf(
                    'Pipeline: %s | Task: %s | Action: %s | Server: %s | Started processing first action',
                    $this->currentAction->getExecution()->getPipelineUuid(),
                    $this->currentAction->getExecution()->getTaskUuid(),
                    $this->currentAction->getExecution()->getActionUuid(),
                    $this->currentAction->getExecution()->getServerUuid(),
                ));

                $this->currentAction->setExecutionState(ExecutionState::EXECUTING);
                $this->markOtherActionsAs(ExecutionState::WAITING);
                $this->startActionRunner();
                break;
            case ExecutionState::WAITING:
                $this->debugLine(sprintf(
                    'Pipeline: %s | Task: %s | Action: %s | Server: %s | Started processing new action',
                    $this->currentAction->getExecution()->getPipelineUuid(),
                    $this->currentAction->getExecution()->getTaskUuid(),
                    $this->currentAction->getExecution()->getActionUuid(),
                    $this->currentAction->getExecution()->getServerUuid(),
                ));

                if ($this->hasFailedActions()) {
                    $this->markOtherActionsAs(ExecutionState::CANCELED);
                } else {
                    $this->currentAction->setExecutionState(ExecutionState::EXECUTING);
                    $this->startActionRunner();
                }
                break;
            case ExecutionState::CANCELED:
                $this->debugWarn(sprintf(
                    'Pipeline: %s | Task: %s | Action: %s | Server: %s | Skipping cancelled action action',
                    $this->currentAction->getExecution()->getPipelineUuid(),
                    $this->currentAction->getExecution()->getTaskUuid(),
                    $this->currentAction->getExecution()->getActionUuid(),
                    $this->currentAction->getExecution()->getServerUuid(),
                ));
                break;
            case ExecutionState::SUCCESS:
                $this->debugInfo(sprintf(
                    'Pipeline: %s | Task: %s | Action: %s | Server: %s | Successfully processed action',
                    $this->currentAction->getExecution()->getPipelineUuid(),
                    $this->currentAction->getExecution()->getTaskUuid(),
                    $this->currentAction->getExecution()->getActionUuid(),
                    $this->currentAction->getExecution()->getServerUuid(),
                ));
                break;
            case ExecutionState::FAILURE:
                $this->debugError(sprintf(
                    'Pipeline: %s | Task: %s | Action: %s | Server: %s | Failed to process action action',
                    $this->currentAction->getExecution()->getPipelineUuid(),
                    $this->currentAction->getExecution()->getTaskUuid(),
                    $this->currentAction->getExecution()->getActionUuid(),
                    $this->currentAction->getExecution()->getServerUuid(),
                ));
                break;
        }
    }

    /**
     * Start action runner
     * @return void
     */
    public function startActionRunner(): void
    {
        $this->currentAction->getRunner()->execute(function (int $exitCode, array $output = []): void {
            $stateCode = $exitCode === 0 ? ExecutionState::SUCCESS : ExecutionState::FAILURE;

            if ($this->currentAction->shouldFailOnError()) {
                if ($stateCode === ExecutionState::FAILURE) {
                    $this->markOtherActionsAs(ExecutionState::CANCELED);
                }
            }

            $this->currentAction
                ->setExecutionState($stateCode)
                ->setExecutionLog($exitCode, $output);
        });
    }

    /**
     * Select new action to work on
     * @return void
     */
    public function selectNextAction(): void
    {
        if ($this->currentAction === null) {
            $this->currentAction = $this->getActions()->first();
        }

        if ($this->currentAction !== null) {
            $actionState = $this->currentAction->getExecutionState();
            if (in_array($actionState, [ExecutionState::SUCCESS, ExecutionState::FAILURE])) {
                $this->getActions()->forget(0);
                $this->currentAction = $this->getActions()->first();
            }
        }
    }

    /**
     * Update other actions and set their state to provided value
     * @param int $state
     * @return $this
     */
    public function markOtherActionsAs(int $state): ServerEntity
    {
        $this->getActions()->filter(function (ActionEntity $actionEntity): bool {
            return $actionEntity !== $this->currentAction;
        })->each(function (ActionEntity $actionEntity) use ($state): void {
            $actionEntity->setExecutionState($state, [ ExecutionState::EXECUTING ]);
        });
        return $this;
    }

    /**
     * Resolve service instance
     * @return ServiceInterface
     */
    private function resolveServerInstance(): ServiceInterface
    {
        $identifier = $this->identifier;

        try {
            $repository = app()->make(ServiceRepositoryInterface::class);
            $instance = $repository->findBy('uuid', $identifier);
        } catch (Throwable) {
            $instance = Service::uuid($identifier);
        }

        return $instance;
    }
}
