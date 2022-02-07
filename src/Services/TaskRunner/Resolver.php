<?php

namespace ConsulConfigManager\Tasks\Services\TaskRunner;

use Illuminate\Database\Eloquent\Collection;
use ConsulConfigManager\Tasks\Enums\ExecutionState;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskActionInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionInterface;
use ConsulConfigManager\Consul\Agent\Interfaces\ServiceInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;
use ConsulConfigManager\Tasks\Services\TaskRunner\Tasks\RemoteTask;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\TaskEntity;
use ConsulConfigManager\Tasks\Interfaces\TaskActionRepositoryInterface;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\ActionEntity;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\ServerEntity;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionRepositoryInterface;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\PipelineEntity;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionRepositoryInterface;

/**
 * Class Resolver
 * @package ConsulConfigManager\Tasks\Services\TaskRunner
 */
class Resolver extends LoggableClass
{
    /**
     * Pipeline identifier
     * @var string|int
     */
    private string|int $pipelineIdentifier;

    /**
     * Pipeline repository instance
     * @var PipelineRepositoryInterface
     */
    private PipelineRepositoryInterface $pipelineRepository;

    /**
     * Pipeline Task repository instance
     * @var PipelineTaskRepositoryInterface
     */
    private PipelineTaskRepositoryInterface $pipelineTaskRepository;

    /**
     * Pipeline Execution repository instance
     * @var PipelineExecutionRepositoryInterface
     */
    private PipelineExecutionRepositoryInterface $pipelineExecutionRepository;

    /**
     * Task repository instance
     * @var TaskRepositoryInterface
     */
    private TaskRepositoryInterface $taskRepository;

    /**
     * Task Action repository instance
     * @var TaskActionRepositoryInterface
     */
    private TaskActionRepositoryInterface $taskActionRepository;

    /**
     * Task Execution repository instance
     * @var TaskExecutionRepositoryInterface
     */
    private TaskExecutionRepositoryInterface $taskExecutionRepository;

    /**
     * Action repository interface
     * @var ActionRepositoryInterface
     */
    private ActionRepositoryInterface $actionRepository;

    /**
     * Action Execution repository instance
     * @var ActionExecutionRepositoryInterface
     */
    private ActionExecutionRepositoryInterface $actionExecutionRepository;

    /**
     * Configured pipeline entity
     * @var PipelineEntity
     */
    private PipelineEntity $pipelineEntity;

    /**
     * Resolver constructor.
     * @param string|int $pipelineIdentifier
     * @return void
     * @throws BindingResolutionException
     */
    public function __construct(string|int $pipelineIdentifier)
    {
        $this->pipelineIdentifier = $pipelineIdentifier;
    }

    /**
     * Get configured pipeline entity
     * @return PipelineEntity
     */
    public function getPipelineEntity(): PipelineEntity
    {
        return $this->pipelineEntity;
    }

    /**
     * @inheritDoc
     * @throws BindingResolutionException
     */
    public function bootstrap(): void
    {
        $this->pipelineRepository = app()->make(PipelineRepositoryInterface::class);
        $this->pipelineTaskRepository = app()->make(PipelineTaskRepositoryInterface::class);
        $this->pipelineExecutionRepository = app()->make(PipelineExecutionRepositoryInterface::class);
        $this->taskRepository = app()->make(TaskRepositoryInterface::class);
        $this->taskActionRepository = app()->make(TaskActionRepositoryInterface::class);
        $this->taskExecutionRepository = app()->make(TaskExecutionRepositoryInterface::class);
        $this->actionRepository = app()->make(ActionRepositoryInterface::class);
        $this->actionExecutionRepository = app()->make(ActionExecutionRepositoryInterface::class);
        $this->generatePipelineEntity();
    }

    /**
     * Resolve pipeline by the specified identifier
     * @return PipelineInterface
     */
    private function resolvePipeline(): PipelineInterface
    {
        return $this->pipelineRepository->findByManyOrFail(
            fields: ['id', 'uuid'],
            value: $this->pipelineIdentifier,
        );
    }

    /**
     * Resolve tasks for pipeline
     * @param PipelineInterface $pipeline
     * @return TaskInterface[]
     * @throws BindingResolutionException
     */
    private function resolvePipelineTasks(PipelineInterface $pipeline): array
    {
        $pipelineTasks = $this->pipelineTaskRepository->list($pipeline->getUuid());
        $tasks = [];

        /**
         * @var PipelineTaskInterface $pipelineTask
         */
        foreach ($pipelineTasks as $pipelineTask) {
            $tasks[$pipelineTask->getOrder() - 1] = $this->taskRepository->findBy('uuid', $pipelineTask->getTaskUuid());
        }

        ksort($tasks);
        return $tasks;
    }

    /**
     * Resolve actions for task
     * @param TaskInterface $task
     * @return ActionInterface[]
     * @throws BindingResolutionException
     */
    private function resolveTaskActions(TaskInterface $task): array
    {
        $taskActions = $this->taskActionRepository->list($task->getUuid());
        $actions = [];

        /**
         * @var TaskActionInterface $taskAction
         */
        foreach ($taskActions as $taskAction) {
            $actions[$taskAction->getOrder() - 1] = $this->actionRepository->findBy('uuid', $taskAction->getActionUuid());
        }

        ksort($actions);
        return $actions;
    }

    /**
     * Resolve servers for action
     * @param ActionInterface $action
     * @return Collection|ServiceInterface[]
     */
    private function resolveActionServers(ActionInterface $action): Collection
    {
        return $action->hosts;
    }

    /**
     * Creates new pipeline execution entry in the database
     * @param string $pipelineIdentifier
     * @return PipelineExecutionInterface
     */
    private function createNewPipelineExecution(string $pipelineIdentifier): PipelineExecutionInterface
    {
        return $this->pipelineExecutionRepository->create(
            $pipelineIdentifier,
            ExecutionState::CREATED,
        );
    }

    /**
     * Creates new task execution entry in the database
     * @param string $taskIdentifier
     * @param string $pipelineIdentifier
     * @param string $executionIdentifier
     * @return TaskExecutionInterface
     */
    private function createNewTaskExecution(
        string $taskIdentifier,
        string $pipelineIdentifier,
        string $executionIdentifier
    ): TaskExecutionInterface {
        return $this->taskExecutionRepository->create(
            taskIdentifier: $taskIdentifier,
            pipelineIdentifier: $pipelineIdentifier,
            executionIdentifier: $executionIdentifier
        );
    }

    /**
     * Creates new action execution entry in the database
     * @param string $serverIdentifier
     * @param string $actionIdentifier
     * @param string $taskIdentifier
     * @param string $pipelineIdentifier
     * @param string $executionIdentifier
     * @return ActionExecutionInterface
     */
    private function createNewActionExecution(
        string $serverIdentifier,
        string $actionIdentifier,
        string $taskIdentifier,
        string $pipelineIdentifier,
        string $executionIdentifier,
    ): ActionExecutionInterface {
        return $this->actionExecutionRepository->create(
            serverIdentifier: $serverIdentifier,
            actionIdentifier: $actionIdentifier,
            taskIdentifier: $taskIdentifier,
            pipelineIdentifier: $pipelineIdentifier,
            executionIdentifier: $executionIdentifier,
        );
    }

    /**
     * Generate pipeline entity for later use
     * @return void
     * @throws BindingResolutionException
     */
    private function generatePipelineEntity(): void
    {
        $pipeline = $this->resolvePipeline();
        $pipelineIdentifier = $pipeline->getUuid();

        $execution = $this->createNewPipelineExecution($pipelineIdentifier);
        $executionIdentifier = $execution->getUuid();

        $pipelineTasks = $this->resolvePipelineTasks($pipeline);

        $pipelineEntity = new PipelineEntity($execution);
        $pipelineEntity->setDebug($this->getDebug())->setOutputInterface($this->getOutputInterface())->bootstrap();

        foreach ($pipelineTasks as $pipelineTask) {
            $taskIdentifier = $pipelineTask->getUuid();
            $taskExecution = $this->createNewTaskExecution($taskIdentifier, $pipelineIdentifier, $executionIdentifier);

            $taskActions = $this->resolveTaskActions($pipelineTask);

            $taskEntity = new TaskEntity($taskExecution);
            $taskEntity->setDebug($this->getDebug())->setOutputInterface($this->getOutputInterface())->bootstrap();

            foreach ($taskActions as $taskAction) {
                $actionIdentifier = $taskAction->getUuid();
                $actionServers = $this->resolveActionServers($taskAction);

                foreach ($actionServers as $server) {
                    $serverIdentifier = $server->getUuid();

                    if (!$taskEntity->hasServer($serverIdentifier)) {
                        $serverEntity = new ServerEntity($serverIdentifier);

                        $serverEntity->setDebug($this->getDebug())->setOutputInterface($this->getOutputInterface())
                            ->bootstrap();

                        $taskEntity->addServer($serverEntity);
                    }

                    $actionExecution = $this->createNewActionExecution(
                        $serverIdentifier,
                        $actionIdentifier,
                        $taskIdentifier,
                        $pipelineIdentifier,
                        $executionIdentifier,
                    );

                    $actionRunner = $this->createRunner(
                        $execution,
                        $pipeline,
                        $pipelineTask,
                        $taskAction,
                        $server,
                    );

                    $actionEntity = new ActionEntity($actionExecution, $actionRunner);

                    $actionEntity->setDebug($this->getDebug())->setOutputInterface($this->getOutputInterface())
                        ->bootstrap();

                    $taskEntity->findServer($serverIdentifier)->addAction($actionEntity);
                }
            }

            $pipelineEntity->addTask($taskEntity);
        }

        $this->pipelineEntity = $pipelineEntity;
    }

    /**
     * Create new runner
     * @param PipelineExecutionInterface $execution
     * @param PipelineInterface $pipeline
     * @param TaskInterface $task
     * @param ActionInterface $action
     * @param ServiceInterface $server
     * @return RemoteTask
     */
    private function createRunner(
        PipelineExecutionInterface $execution,
        PipelineInterface $pipeline,
        TaskInterface $task,
        ActionInterface $action,
        ServiceInterface $server,
    ): RemoteTask {
        $remoteTask = new RemoteTask($server->getAddress(), $server->getPort());
        $remoteTask->setExecutionIdentifier($execution->getUuid());
        $remoteTask->setPipelineIdentifier($pipeline->getUuid());
        $remoteTask->setTaskIdentifier($task->getUuid());
        $remoteTask->setActionIdentifier($action->getUuid());
        $remoteTask->setServerIdentifier($server->getUuid());
        $remoteTask->setCommand($action->getCommand());
        $remoteTask->setArguments($action->getArguments());
        $remoteTask->setRunAs($action->getRunAs());
        $remoteTask->setUseSudo($action->isUsingSudo());
        $remoteTask->setFailOnError($action->isFailingOnError());
        $remoteTask->setWorkingDirectory($action->getWorkingDirectory());
        return $remoteTask;
    }
}
