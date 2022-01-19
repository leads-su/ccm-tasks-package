<?php

namespace ConsulConfigManager\Tasks\Test\Integration;

use ConsulConfigManager\Tasks\Models\Task;
use ConsulConfigManager\Tasks\Models\Action;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\Enums\TaskType;
use ConsulConfigManager\Tasks\Models\Pipeline;
use ConsulConfigManager\Consul\Agent\Models\Service;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Consul\Agent\Interfaces\ServiceInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionRepositoryInterface;
use ConsulConfigManager\Consul\Agent\Interfaces\ServiceRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionRepositoryInterface;

/**
 * Class RunnerIntegrationTest
 * @package ConsulConfigManager\Tasks\Test\Integration
 */
class RunnerIntegrationTest extends TestCase
{
    /**
     * Identifier for pipeline
     * @var string
     */
    private string $pipelineIdentifier = 'f9989546-8e8d-4497-8951-dc839cf6f4d4';

    /**
     * Identifier for first successful task
     * @var string
     */
    private string $firstSuccessfulTaskIdentifier = '05993127-d3a8-4c4c-9be5-537f0bac9bfc';

    /**
     * Identifier for second successful task
     * @var string
     */
    private string $secondSuccessfulTaskIdentifier = '63460123-5cb1-450f-b196-90eb0bfb196f';

    /**
     * Identifier for failing task
     * @var string
     */
    private string $failingTaskIdentifier = '11f25cdb-b82f-4c45-ab6b-f72fe52a3462';

    /**
     * Identifier for first successful action
     * @var string
     */
    private string $firstSuccessfulActionIdentifier = '14bced49-d292-4d8c-856d-8027b630d8c3';

    /**
     * Identifier for second successful action
     * @var string
     */
    private string $secondSuccessfulActionIdentifier = 'ffd273f9-ccec-43ab-84a6-9b0125dca97d';

    /**
     * Identifier for failing action
     * @var string
     */
    private string $failingActionIdentifier = 'a7b18f1e-8c0b-433c-9be7-1a7966411dfb';

    /**
     * Identifier for first server
     * @var string
     */
    private string $firstServerIdentifier = '29845927-1544-4c7f-afa5-2b597a71c76d';

    /**
     * Identifier for second server
     * @var string
     */
    private string $secondServerIdentifier = '18a6f634-8d2c-469c-96e5-c640cc21e0a0';

    /**
     * @return void
     */
    public function testShouldPassIfCanCreateBasePipelineItemsThroughModels(): void
    {
        $this->createPipeline();
        $this->createFirstSuccessfulTask();
        $this->createSecondSuccessfulTask();
        $this->createFailingTask();
        $this->createFirstSuccessfulAction();
        $this->createSecondSuccessfulAction();
        $this->createFailingAction();
        $this->createFirstServer();
        $this->createSecondServer();
    }

    /**
     * @return void
     */
    public function testShouldPassIfCanCreateBasePipelineItemsThroughRepositories(): void
    {
        $this->createPipeline(true);
        $this->createFirstSuccessfulTask(true);
        $this->createSecondSuccessfulTask(true);
        $this->createFailingTask(true);
        $this->createFirstSuccessfulAction(true);
        $this->createSecondSuccessfulAction(true);
        $this->createFailingAction(true);
        $this->createFirstServer(true);
        $this->createSecondServer(true);
    }

    /**
     * Create new pipeline
     * @param bool $repository
     * @return PipelineInterface
     */
    private function createPipeline(bool $repository = false): PipelineInterface
    {
        $instance = $repository ?
            $this->createPipelineThroughRepository() :
            $this->createPipelineThroughModel();

        $this->assertNotNull($this->pipelineRepository()->find($instance->getID()));
        return $instance;
    }

    /**
     * Create first successful task
     * @param bool $repository
     * @return TaskInterface
     */
    private function createFirstSuccessfulTask(bool $repository = false): TaskInterface
    {
        $instance = $repository ?
            $this->createFirstSuccessfulTaskThroughRepository() :
            $this->createFirstSuccessfulTaskThroughModel();

        $this->assertNotNull($this->taskRepository()->find($instance->getID()));
        return $instance;
    }

    /**
     * Create second successful task
     * @param bool $repository
     * @return TaskInterface
     */
    private function createSecondSuccessfulTask(bool $repository = false): TaskInterface
    {
        $instance = $repository ?
            $this->createSecondSuccessfulTaskThroughRepository() :
            $this->createSecondSuccessfulTaskThroughModel();

        $this->assertNotNull($this->taskRepository()->find($instance->getID()));
        return $instance;
    }

    /**
     * Create failing task
     * @param bool $repository
     * @return TaskInterface
     */
    private function createFailingTask(bool $repository = false): TaskInterface
    {
        $instance = $repository ?
            $this->createFailingTaskThroughRepository() :
            $this->createFailingTaskThroughModel();

        $this->assertNotNull($this->taskRepository()->find($instance->getID()));
        return $instance;
    }

    /**
     * Create first successful action
     * @param bool $repository
     * @return ActionInterface
     */
    private function createFirstSuccessfulAction(bool $repository = false): ActionInterface
    {
        $instance = $repository ?
            $this->createFirstSuccessfulActionThroughRepository() :
            $this->createFirstSuccessfulActionThroughModel();

        $this->assertNotNull($this->actionRepository()->find($instance->getID()));
        return $instance;
    }

    /**
     * Create second successful action
     * @param bool $repository
     * @return ActionInterface
     */
    private function createSecondSuccessfulAction(bool $repository = false): ActionInterface
    {
        $instance = $repository ?
            $this->createSecondSuccessfulActionThroughRepository() :
            $this->createSecondSuccessfulActionThroughModel();

        $this->assertNotNull($this->actionRepository()->find($instance->getID()));
        return $instance;
    }

    /**
     * Create failing action
     * @param bool $repository
     * @return ActionInterface
     */
    private function createFailingAction(bool $repository = false): ActionInterface
    {
        $instance = $repository ?
            $this->createFailingActionThroughRepository() :
            $this->createFailingActionThroughModel();

        $this->assertNotNull($this->actionRepository()->find($instance->getID()));
        return $instance;
    }

    /**
     * Create first server
     * @param bool $repository
     * @return ServiceInterface
     */
    private function createFirstServer(bool $repository = false): ServiceInterface
    {
        $instance = $repository ?
            $this->createFirstServerThroughRepository() :
            $this->createFirstServerThroughModel();

        $this->assertNotNull($this->serverRepository()->find('ccm-first-server.local-172.18.0.235'));
        return $instance;
    }

    /**
     * Create second server
     * @param bool $repository
     * @return ServiceInterface
     */
    private function createSecondServer(bool $repository = false): ServiceInterface
    {
        $instance = $repository ?
            $this->createSecondServerThroughRepository() :
            $this->createSecondServerThroughModel();

        $this->assertNotNull($this->serverRepository()->find('ccm-second-server.local-172.18.0.235'));
        return $instance;
    }

    /**
     * Create first successful task through repository
     * @return TaskInterface
     */
    private function createFirstSuccessfulTaskThroughRepository(): TaskInterface
    {
        $instance = $this->createNewTaskThroughRepository(
            'Example Successful Task 1',
            'Example Successful Task 1 Description'
        );
        $this->firstSuccessfulTaskIdentifier = $instance->getUuid();
        return $instance;
    }

    /**
     * Create first successful task through model
     * @return TaskInterface
     */
    private function createFirstSuccessfulTaskThroughModel(): TaskInterface
    {
        return $this->createNewTaskThroughModel(
            $this->firstSuccessfulTaskIdentifier,
            'Example Successful Task 1',
            'Example Successful Task 1 Description'
        );
    }

    /**
     * Create second successful task through repository
     * @return TaskInterface
     */
    private function createSecondSuccessfulTaskThroughRepository(): TaskInterface
    {
        $instance = $this->createNewTaskThroughRepository(
            'Example Successful Task 2',
            'Example Successful Task 2 Description'
        );
        $this->secondSuccessfulTaskIdentifier = $instance->getUuid();
        return $instance;
    }

    /**
     * Create second successful task through model
     * @return TaskInterface
     */
    private function createSecondSuccessfulTaskThroughModel(): TaskInterface
    {
        return $this->createNewTaskThroughModel(
            $this->secondSuccessfulTaskIdentifier,
            'Example Successful Task 2',
            'Example Successful Task 2 Description'
        );
    }

    /**
     * Create failing task through repository
     * @return TaskInterface
     */
    private function createFailingTaskThroughRepository(): TaskInterface
    {
        $instance = $this->createNewTaskThroughRepository(
            'Example Failing Task',
            'Example Failing Task Description'
        );
        $this->failingTaskIdentifier = $instance->getUuid();
        return $instance;
    }

    /**
     * Create failing task through model
     * @return TaskInterface
     */
    private function createFailingTaskThroughModel(): TaskInterface
    {
        return $this->createNewTaskThroughModel(
            $this->failingTaskIdentifier,
            'Example Failing Task',
            'Example Failing Task Description'
        );
    }

    /**
     * Create first successful action through repository
     * @return ActionInterface
     */
    private function createFirstSuccessfulActionThroughRepository(): ActionInterface
    {
        $instance = $this->createNewActionThroughRepository(
            'Example Successful Action 1',
            'Example Successful Action 1 Description',
            TaskType::REMOTE,
            'php',
            [ 'success.php' ],
            '/home/scripts',
            null,
            true,
            true,
        );
        $this->firstSuccessfulActionIdentifier = $instance->getUuid();
        return $instance;
    }

    /**
     * Create first successful action through model
     * @return ActionInterface
     */
    private function createFirstSuccessfulActionThroughModel(): ActionInterface
    {
        return $this->createNewActionThroughModel(
            $this->firstSuccessfulActionIdentifier,
            'Example Successful Action 1',
            'Example Successful Action 1 Description',
            TaskType::REMOTE,
            'php',
            [ 'success.php' ],
            '/home/scripts',
            null,
            true,
            true,
        );
    }

    /**
     * Create second successful action through repository
     * @return ActionInterface
     */
    private function createSecondSuccessfulActionThroughRepository(): ActionInterface
    {
        $instance = $this->createNewActionThroughRepository(
            'Example Successful Action 2',
            'Example Successful Action 2 Description',
            TaskType::REMOTE,
            'php',
            [ 'success.php' ],
            '/home/scripts',
            null,
            true,
            true,
        );
        $this->secondSuccessfulActionIdentifier = $instance->getUuid();
        return $instance;
    }

    /**
     * Create second successful action through model
     * @return ActionInterface
     */
    private function createSecondSuccessfulActionThroughModel(): ActionInterface
    {
        return $this->createNewActionThroughModel(
            $this->secondSuccessfulActionIdentifier,
            'Example Successful Action 2',
            'Example Successful Action 2 Description',
            TaskType::REMOTE,
            'php',
            [ 'success.php' ],
            '/home/scripts',
            null,
            true,
            true,
        );
    }

    /**
     * Create failing action through repository
     * @return ActionInterface
     */
    private function createFailingActionThroughRepository(): ActionInterface
    {
        $instance = $this->createNewActionThroughRepository(
            'Example Failing Action',
            'Example Failing Action Description',
            TaskType::REMOTE,
            'php',
            [ 'failure.php' ],
            '/home/scripts',
            null,
            true,
            true,
        );
        $this->failingActionIdentifier = $instance->getUuid();
        return $instance;
    }

    /**
     * Create failing action through model
     * @return ActionInterface
     */
    private function createFailingActionThroughModel(): ActionInterface
    {
        return $this->createNewActionThroughModel(
            $this->failingActionIdentifier,
            'Example Failing Action',
            'Example Failing Action Description',
            TaskType::REMOTE,
            'php',
            [ 'failure.php' ],
            '/home/scripts',
            null,
            true,
            true,
        );
    }

    /**
     * Create new pipeline through repository
     * @return PipelineInterface
     */
    private function createPipelineThroughRepository(): PipelineInterface
    {
        $instance = $this->pipelineRepository()->create(
            'Example Pipeline',
            'Example Pipeline Description'
        );
        $this->pipelineIdentifier = $instance->getUuid();
        return $instance;
    }

    /**
     * Create new pipeline through model
     * @return PipelineInterface
     */
    private function createPipelineThroughModel(): PipelineInterface
    {
        $model = new Pipeline();
        $model->setUuid($this->pipelineIdentifier);
        $model->setName('Example Pipeline');
        $model->setDescription('Example Pipeline Description');
        $model->save();
        return $model;
    }

    /**
     * Create first server through repository
     * @return ServiceInterface
     */
    private function createFirstServerThroughRepository(): ServiceInterface
    {
        $instance = $this->createNewServerThroughRepository(
            'ccm-first-server.local-172.18.0.235',
            'ccm',
            '172.18.0.235',
            32175,
            'leads',
            [],
            [],
            1,
            'testing',
        );
        $this->firstServerIdentifier = $instance->getUuid();
        return $instance;
    }

    /**
     * Create first server through model
     * @return ServiceInterface
     */
    private function createFirstServerThroughModel(): ServiceInterface
    {
        return $this->createNewServerThroughModel(
            $this->firstServerIdentifier,
            'ccm-first-server.local-172.18.0.235',
            'ccm',
            '172.18.0.235',
            32175,
            'leads',
            [],
            [],
            1,
            'testing',
        );
    }

    /**
     * Create second server through repository
     * @return ServiceInterface
     */
    private function createSecondServerThroughRepository(): ServiceInterface
    {
        $instance = $this->createNewServerThroughRepository(
            'ccm-second-server.local-172.18.0.235',
            'ccm',
            '172.18.0.235',
            32175,
            'leads',
            [],
            [],
            1,
            'testing',
        );
        $this->secondServerIdentifier = $instance->getUuid();
        return $instance;
    }

    /**
     * Create second server through model
     * @return ServiceInterface
     */
    private function createSecondServerThroughModel(): ServiceInterface
    {
        return $this->createNewServerThroughModel(
            $this->secondServerIdentifier,
            'ccm-second-server.local-172.18.0.235',
            'ccm',
            '172.18.0.235',
            32175,
            'leads',
            [],
            [],
            1,
            'testing',
        );
    }

    /**
     * Create new task through repository
     * @param string $name
     * @param string $description
     * @param int $type
     * @return TaskInterface
     */
    private function createNewTaskThroughRepository(string $name, string $description, int $type = TaskType::REMOTE): TaskInterface
    {
        return $this->taskRepository()->create(
            $name,
            $description,
            $type,
        );
    }

    /**
     * Create new task through model
     * @param string $identifier
     * @param string $name
     * @param string $description
     * @param int $type
     * @return TaskInterface
     */
    private function createNewTaskThroughModel(string $identifier, string $name, string $description, int $type = TaskType::REMOTE): TaskInterface
    {
        $model = new Task();
        $model->setUuid($identifier);
        $model->setName($name);
        $model->setDescription($description);
        $model->setType($type);
        $model->save();
        return $model;
    }

    /**
     * Create new action through repository
     * @param string $name
     * @param string $description
     * @param int $type
     * @param string $command
     * @param array $arguments
     * @param string|null $workingDirectory
     * @param string|null $runAs
     * @param bool $useSudo
     * @param bool $failOnError
     * @return ActionInterface
     */
    private function createNewActionThroughRepository(
        string $name,
        string $description,
        int $type,
        string $command,
        array $arguments,
        ?string $workingDirectory = null,
        ?string $runAs = null,
        bool $useSudo = false,
        bool $failOnError = true,
    ): ActionInterface {
        return $this->actionRepository()->create(
            $name,
            $description,
            $type,
            $command,
            $arguments,
            $workingDirectory,
            $runAs,
            $useSudo,
            $failOnError,
        );
    }

    /**
     * Create new action through model
     * @param string $identifier
     * @param string $name
     * @param string $description
     * @param int $type
     * @param string $command
     * @param array $arguments
     * @param string|null $workingDirectory
     * @param string|null $runAs
     * @param bool $useSudo
     * @param bool $failOnError
     * @return ActionInterface
     */
    private function createNewActionThroughModel(
        string $identifier,
        string $name,
        string $description,
        int $type,
        string $command,
        array $arguments,
        ?string $workingDirectory = null,
        ?string $runAs = null,
        bool $useSudo = false,
        bool $failOnError = true,
    ): ActionInterface {
        $model = new Action();
        $model->setUuid($identifier);
        $model->setName($name);
        $model->setDescription($description);
        $model->setType($type);
        $model->setCommand($command);
        $model->setArguments($arguments);
        $model->setWorkingDirectory($workingDirectory);
        $model->setRunAs($runAs);
        $model->useSudo($useSudo);
        $model->failOnError($failOnError);
        $model->save();
        return $model;
    }

    /**
     * Create new server through repository
     * @param string $name
     * @param string $service
     * @param string $address
     * @param int $port
     * @param string $datacenter
     * @param array $tags
     * @param array $meta
     * @param int $online
     * @param string $environment
     * @return ServiceInterface
     */
    private function createNewServerThroughRepository(
        string $name,
        string $service,
        string $address,
        int $port,
        string $datacenter,
        array $tags,
        array $meta,
        int $online,
        string $environment,
    ): ServiceInterface {
        return $this->serverRepository()->create([
            'id'                =>  $name,
            'service'           =>  $service,
            'tags'              =>  $tags,
            'meta'              =>  array_merge($meta, [
                'environment'   =>  $environment,
            ]),
            'port'              =>  $port,
            'address'           =>  $address,
            'online'            =>  $online,
            'datacenter'        =>  $datacenter,
        ]);
    }

    /**
     * Create new server through model
     * @param string $identifier
     * @param string $name
     * @param string $service
     * @param string $address
     * @param int $port
     * @param string $datacenter
     * @param array $tags
     * @param array $meta
     * @param int $online
     * @param string $environment
     * @return ServiceInterface
     */
    private function createNewServerThroughModel(
        string $identifier,
        string $name,
        string $service,
        string $address,
        int $port,
        string $datacenter,
        array $tags,
        array $meta,
        int $online,
        string $environment,
    ): ServiceInterface {
        $model = new Service();
        $model->setUuid($identifier);
        $model->setIdentifier($name);
        $model->setService($service);
        $model->setAddress($address);
        $model->setPort($port);
        $model->setDatacenter($datacenter);
        $model->setTags($tags);
        $model->setMeta($meta);
        $model->setOnline($online);
        $model->setEnvironment($environment);
        $model->save();
        return $model;
    }

    /**
     * Create new instance of Pipeline repository
     * @return PipelineRepositoryInterface
     */
    private function pipelineRepository(): PipelineRepositoryInterface
    {
        return $this->app->make(PipelineRepositoryInterface::class);
    }

    /**
     * Create new instance of Pipeline Execution repository
     * @return PipelineExecutionRepositoryInterface
     */
    private function pipelineExecutionRepository(): PipelineExecutionRepositoryInterface
    {
        return $this->app->make(PipelineExecutionRepositoryInterface::class);
    }

    /**
     * Create new instance of Task repository
     * @return TaskRepositoryInterface
     */
    private function taskRepository(): TaskRepositoryInterface
    {
        return $this->app->make(TaskRepositoryInterface::class);
    }

    /**
     * Create new instance of Task Execution repository
     * @return TaskExecutionRepositoryInterface
     */
    private function taskExecutionRepository(): TaskExecutionRepositoryInterface
    {
        return $this->app->make(TaskExecutionRepositoryInterface::class);
    }

    /**
     * Create new instance of Action repository
     * @return ActionRepositoryInterface
     */
    private function actionRepository(): ActionRepositoryInterface
    {
        return $this->app->make(ActionRepositoryInterface::class);
    }

    /**
     * Create new instance of Action Execution repository
     * @return ActionExecutionRepositoryInterface
     */
    private function actionExecutionRepository(): ActionExecutionRepositoryInterface
    {
        return $this->app->make(ActionExecutionRepositoryInterface::class);
    }

    /**
     * Create new instance of Service repository
     * @return ServiceRepositoryInterface
     */
    private function serverRepository(): ServiceRepositoryInterface
    {
        return $this->app->make(ServiceRepositoryInterface::class);
    }
}
