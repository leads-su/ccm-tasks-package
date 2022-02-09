<?php

namespace ConsulConfigManager\Tasks\Test\Integration;

use Exception;
use Illuminate\Support\Str;
use ConsulConfigManager\Tasks\Models\Task;
use ConsulConfigManager\Tasks\Models\Action;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\Enums\TaskType;
use ConsulConfigManager\Tasks\Models\Pipeline;
use ConsulConfigManager\Tasks\Enums\ActionType;
use ConsulConfigManager\Consul\Agent\Models\Service;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionHostInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskActionInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskInterface;
use ConsulConfigManager\Consul\Agent\Interfaces\ServiceInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionHostRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskActionRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionRepositoryInterface;
use ConsulConfigManager\Consul\Agent\Interfaces\ServiceRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionRepositoryInterface;

/**
 * Class AbstractRunnerIntegrationTest
 * @package ConsulConfigManager\Tasks\Test\Integration
 */
abstract class AbstractRunnerIntegrationTest extends TestCase
{
    /**
     * Pipeline identifier
     * @var string
     */
    protected string $pipelineIdentifier = '';

    /**
     * List of servers to use for executions
     * @var array|string[]
     */
    protected array $serverAddresses = [
        '172.18.0.235',
    ];

    /**
     * List of servers identifiers
     * @var array
     */
    protected array $serverIdentifiers = [];

    /**
     * List of actions identifiers
     * @var array
     */
    protected array $actionIdentifiers = [];

    /**
     * List of Action => Server relation
     * @var array
     */
    protected array $actionServerReference = [];

    /**
     * List of identifiers for failing actions
     * @var array
     */
    protected array $failingActionIdentifiers = [];

    /**
     * List of servers containing failing action
     * @var array
     */
    protected array $failingActionServers = [];

    /**
     * List of tasks identifiers
     * @var array
     */
    protected array $taskIdentifiers = [];

    /**
     * List of identifiers for failing tasks
     * @var array
     */
    protected array $failingTaskIdentifiers = [];

    /**
     * List of Task => Action relation
     * @var array
     */
    protected array $taskActionReference = [];

    /**
     * List of Pipeline => Task relation
     * @var array
     */
    protected array $pipelineTaskReference = [];

    /**
     * Create new server
     * @param bool $useSameAddress
     * @param bool $useRepository
     * @return ServiceInterface
     * @throws Exception
     */
    protected function createNewServer(bool $useSameAddress = false, bool $useRepository = false): ServiceInterface
    {
        $serverIndex = count($this->serverIdentifiers) + 1;
        $serversCount = count($this->serverAddresses);

        if ($serverIndex < $serversCount) {
            $serverAddress = $this->serverAddresses[$serverIndex - 1];
        } else {
            if ($useSameAddress) {
                if ($serversCount === 1) {
                    $serverAddress = $this->serverAddresses[0];
                } else {
                    $serverAddress = rand(0, $serversCount - 1);
                }
            } else {
                throw new Exception('Servers limit reached');
            }
        }

        if ($useRepository) {
            $server = $this->createNewServerThroughRepository(
                serverIndex: $serverIndex,
                serverAddress: $serverAddress,
            );
        } else {
            $server = $this->createNewServerThroughModel(
                serverIndex: $serverIndex,
                serverAddress: $serverAddress,
            );
        }

        $this->serverIdentifiers[] = $server->getUuid();
        return $server;
    }

    /**
     * @param int $serverIndex
     * @param string $serverAddress
     * @return ServiceInterface
     */
    protected function createNewServerThroughModel(int $serverIndex, string $serverAddress): ServiceInterface
    {
        $instance = new Service();
        $instance->setUuid(Str::uuid()->toString());
        $instance->setIdentifier(sprintf('ccm-example%d.local-%s', $serverIndex, $serverAddress));
        $instance->setService('ccm');
        $instance->setTags([]);
        $instance->setMeta([]);
        $instance->setPort(32175);
        $instance->setAddress($serverAddress);
        $instance->setOnline(true);
        $instance->setDatacenter('leads');
        $instance->setEnvironment('testing');
        $instance->save();

        return $instance;
    }

    /**
     * Create new server through repository
     * @param int $serverIndex
     * @param string $serverAddress
     * @return ServiceInterface
     */
    protected function createNewServerThroughRepository(int $serverIndex, string $serverAddress): ServiceInterface
    {
        return $this->serverRepository()->create([
            'id'                =>  sprintf('ccm-example%d.local-%s', $serverIndex, $serverAddress),
            'service'           =>  'ccm',
            'tags'              =>  [],
            'meta'              =>  [
                'environment'   =>  'testing',
            ],
            'port'              =>  32175,
            'address'           =>  $serverAddress,
            'online'            =>  true,
            'datacenter'        =>  'leads',
        ]);
    }

    /**
     * Create new action
     * @param bool $failOnError
     * @param bool $isFailing
     * @param bool $useRepository
     * @return ActionInterface
     */
    protected function createNewAction(
        bool $failOnError = false,
        bool $isFailing = false,
        bool $useRepository = false,
    ): ActionInterface {
        $actionIndex = count($this->actionIdentifiers) + 1;
        if ($useRepository) {
            $action = $this->createNewActionThroughRepository(
                actionIndex: $actionIndex,
                isFailing: $isFailing,
                failOnError: $failOnError,
            );
        } else {
            $action = $this->createNewActionThroughModel(
                actionIndex: $actionIndex,
                isFailing: $isFailing,
                failOnError: $failOnError,
            );
        }

        $this->actionIdentifiers[] = $action->getUuid();
        if ($isFailing) {
            $this->failingActionIdentifiers[] = $action->getUuid();
        }

        return $action;
    }

    /**
     * Create new action through model
     * @param int $actionIndex
     * @param bool $isFailing
     * @param bool $failOnError
     * @return ActionInterface
     */
    protected function createNewActionThroughModel(
        int $actionIndex,
        bool $isFailing,
        bool $failOnError,
    ): ActionInterface {
        $instance = new Action();
        $instance->setUuid(Str::uuid()->toString());
        $instance->setName(sprintf('Example Action #%d', $actionIndex));
        $instance->setDescription(sprintf('Description for Example Action #%d', $actionIndex));
        $instance->setType(ActionType::REMOTE);
        $instance->setCommand('php');
        $instance->setArguments(!$isFailing ? [ 'success.php' ] : [ 'failure.php' ]);
        $instance->setWorkingDirectory('/home/scripts');
        $instance->setRunAs(null);
        $instance->useSudo(false);
        $instance->failOnError($failOnError);
        $instance->save();

        return $instance;
    }

    /**
     * Create new action through repository
     * @param int $actionIndex
     * @param bool $isFailing
     * @param bool $failOnError
     * @return ActionInterface
     */
    protected function createNewActionThroughRepository(
        int $actionIndex,
        bool $isFailing,
        bool $failOnError,
    ): ActionInterface {
        return $this->actionRepository()->create(
            name: sprintf('Example Action #%d', $actionIndex),
            description: sprintf('Description for Example Action #%d', $actionIndex),
            type: ActionType::REMOTE,
            command: 'php',
            arguments: !$isFailing ? [ 'success.php' ] : [ 'failure.php' ],
            workingDirectory: '/home/scripts',
            runAs: null,
            useSudo: false,
            failOnError: $failOnError,
        );
    }

    /**
     * Attach server to action
     * @param ActionInterface $action
     * @param ServiceInterface $server
     * @return ActionHostInterface
     */
    protected function attachServerToAction(ActionInterface $action, ServiceInterface $server): ActionHostInterface
    {
        $actionIdentifier = $action->getUuid();
        $serverIdentifier = $server->getUuid();

        if (!isset($this->actionServerReference[$serverIdentifier])) {
            $this->actionServerReference[$serverIdentifier] = [];
        }

        $this->actionServerReference[$serverIdentifier][] = $actionIdentifier;

        if (in_array($actionIdentifier, $this->failingActionIdentifiers)) {
            $this->failingActionServers[$actionIdentifier] = $serverIdentifier;
        }

        $this->actionHostRepository()->create(
            actionIdentifier: $action->getUuid(),
            serverIdentifier: $server->getUuid(),
        );

        return $this->actionHostRepository()->findExactOrFail(
            actionIdentifier: $action->getUuid(),
            serverIdentifier: $server->getUuid(),
        );
    }

    /**
     * Create new task
     * @param bool $failOnError
     * @param bool $useRepository
     * @return TaskInterface
     */
    protected function createNewTask(
        bool $failOnError = false,
        bool $useRepository = false,
    ): TaskInterface {
        $taskIndex = count($this->taskIdentifiers) + 1;

        if ($useRepository) {
            $task = $this->createNewTaskThroughRepository(
                taskIndex: $taskIndex,
                failOnError: $failOnError
            );
        } else {
            $task = $this->createNewTaskThroughModel(
                taskIndex: $taskIndex,
                failOnError: $failOnError
            );
        }

        if ($failOnError) {
            $this->failingTaskIdentifiers[] = $task->getUuid();
        }

        $this->taskIdentifiers[] = $task->getUuid();

        return $task;
    }

    /**
     * Create new task through model
     * @param int $taskIndex
     * @param bool $failOnError
     * @return TaskInterface
     */
    protected function createNewTaskThroughModel(int $taskIndex, bool $failOnError = false): TaskInterface
    {
        $instance = new Task();
        $instance->setUuid(Str::uuid()->toString());
        $instance->setName(sprintf('Example Task #%d', $taskIndex));
        $instance->setDescription(sprintf('Description for Example Task #%d', $taskIndex));
        $instance->setType(TaskType::REMOTE);
        $instance->failOnError($failOnError);
        $instance->save();

        return $instance;
    }

    /**
     * Create new task through repository
     * @param int $taskIndex
     * @param bool $failOnError
     * @return TaskInterface
     */
    protected function createNewTaskThroughRepository(int $taskIndex, bool $failOnError = false): TaskInterface
    {
        return $this->taskRepository()->create(
            name: sprintf('Example Task #%d', $taskIndex),
            description: sprintf('Description for Example Task #%d', $taskIndex),
            type: TaskType::REMOTE,
            failOnError: $failOnError,
        );
    }

    /**
     * Attach action to task
     * @param TaskInterface $task
     * @param ActionInterface $action
     * @return TaskActionInterface
     * @throws BindingResolutionException
     */
    protected function attachActionToTask(TaskInterface $task, ActionInterface $action): TaskActionInterface
    {
        $taskIdentifier = $task->getUuid();
        $actionIdentifier = $action->getUuid();

        if (!isset($this->taskActionReference[$taskIdentifier])) {
            $this->taskActionReference[$taskIdentifier] = [];
        }
        $order = count($this->taskActionReference[$taskIdentifier]) + 1;

        $this->taskActionReference[$taskIdentifier][] = $actionIdentifier;

        $this->taskActionRepository()->create(
            taskIdentifier: $taskIdentifier,
            actionIdentifier: $actionIdentifier,
            order: $order,
        );

        return $this->taskActionRepository()->get(
            taskIdentifier: $taskIdentifier,
            actionIdentifier: $actionIdentifier,
        );
    }

    /**
     * Create new pipeline
     * @param bool $useRepository
     * @return PipelineInterface
     */
    protected function createNewPipeline(bool $useRepository = false): PipelineInterface
    {
        if ($useRepository) {
            $pipeline = $this->createNewPipelineThroughRepository();
        } else {
            $pipeline = $this->createNewPipelineThroughModel();
        }
        $this->pipelineIdentifier = $pipeline->getUuid();

        return $pipeline;
    }

    /**
     * Create new pipeline through model
     * @return PipelineInterface
     */
    protected function createNewPipelineThroughModel(): PipelineInterface
    {
        $instance = new Pipeline();
        $instance->setUuid(Str::uuid()->toString());
        $instance->setName('Example Pipeline');
        $instance->setDescription('Description for Example Pipeline');
        $instance->save();

        return $instance;
    }

    /**
     * Create new pipeline through repository
     * @return PipelineInterface
     */
    protected function createNewPipelineThroughRepository(): PipelineInterface
    {
        return $this->pipelineRepository()->create(
            name: 'Example Pipeline',
            description: 'Description for Example Pipeline',
        );
    }

    /**
     * Attach task to pipeline
     * @param PipelineInterface $pipeline
     * @param TaskInterface $task
     * @return PipelineTaskInterface
     * @throws BindingResolutionException
     */
    protected function attachTaskToPipeline(PipelineInterface $pipeline, TaskInterface $task): PipelineTaskInterface
    {
        $pipelineIdentifier = $pipeline->getUuid();
        $taskIdentifier = $task->getUuid();

        if (!isset($this->pipelineTaskReference[$pipelineIdentifier])) {
            $this->pipelineTaskReference[$pipelineIdentifier] = [];
        }
        $order = count($this->pipelineTaskReference[$pipelineIdentifier]) + 1;

        $this->pipelineTaskReference[$pipelineIdentifier][] = $taskIdentifier;

        $this->pipelineTaskRepository()->create(
            pipelineIdentifier: $pipelineIdentifier,
            taskIdentifier: $taskIdentifier,
            order: $order,
        );

        return $this->pipelineTaskRepository()->get(
            pipelineIdentifier: $pipelineIdentifier,
            taskIdentifier: $taskIdentifier,
        );
    }

    /**
     * Create new instance of Pipeline repository
     * @return PipelineRepositoryInterface
     */
    protected function pipelineRepository(): PipelineRepositoryInterface
    {
        return $this->app->make(PipelineRepositoryInterface::class);
    }

    /**
     * Create new instance of Pipeline Task repository
     * @return PipelineTaskRepositoryInterface
     */
    protected function pipelineTaskRepository(): PipelineTaskRepositoryInterface
    {
        return $this->app->make(PipelineTaskRepositoryInterface::class);
    }

    /**
     * Create new instance of Pipeline Execution repository
     * @return PipelineExecutionRepositoryInterface
     */
    protected function pipelineExecutionRepository(): PipelineExecutionRepositoryInterface
    {
        return $this->app->make(PipelineExecutionRepositoryInterface::class);
    }

    /**
     * Create new instance of Task repository
     * @return TaskRepositoryInterface
     */
    protected function taskRepository(): TaskRepositoryInterface
    {
        return $this->app->make(TaskRepositoryInterface::class);
    }

    /**
     * Create new instance of Task Action repository
     * @return TaskActionRepositoryInterface
     */
    protected function taskActionRepository(): TaskActionRepositoryInterface
    {
        return $this->app->make(TaskActionRepositoryInterface::class);
    }

    /**
     * Create new instance of Task Execution repository
     * @return TaskExecutionRepositoryInterface
     */
    protected function taskExecutionRepository(): TaskExecutionRepositoryInterface
    {
        return $this->app->make(TaskExecutionRepositoryInterface::class);
    }

    /**
     * Create new instance of Action repository
     * @return ActionRepositoryInterface
     */
    protected function actionRepository(): ActionRepositoryInterface
    {
        return $this->app->make(ActionRepositoryInterface::class);
    }

    /**
     * Create new instance of Action Host repository
     * @return ActionHostRepositoryInterface
     */
    protected function actionHostRepository(): ActionHostRepositoryInterface
    {
        return $this->app->make(ActionHostRepositoryInterface::class);
    }

    /**
     * Create new instance of Action Execution repository
     * @return ActionExecutionRepositoryInterface
     */
    protected function actionExecutionRepository(): ActionExecutionRepositoryInterface
    {
        return $this->app->make(ActionExecutionRepositoryInterface::class);
    }

    /**
     * Create new instance of Service repository
     * @return ServiceRepositoryInterface
     */
    protected function serverRepository(): ServiceRepositoryInterface
    {
        return $this->app->make(ServiceRepositoryInterface::class);
    }
}
