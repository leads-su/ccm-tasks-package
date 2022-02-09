<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Tasks;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\Services\TaskRunner\Tasks\RemoteTask;

/**
 * Class RemoteTaskTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Tasks
 */
class RemoteTaskTest extends TestCase
{
    /**
     * Server identifier for execution
     * @var string
     */
    protected string $serverIdentifier = "a2ec83ed-2a05-437a-b438-58559eaa7ffe";

    /**
     * Action identifier for execution
     * @var string
     */
    protected string $actionIdentifier = "ba6addfc-c524-415e-8f68-b60dd1146840";

    /**
     * Task identifier for execution
     * @var string
     */
    protected string $taskIdentifier = "55dfebdd-a386-482c-a16b-cd2afa4e50cb";

    /**
     * Pipeline identifier for execution
     * @var string
     */
    protected string $pipelineIdentifier = "ec019e70-ec3d-4ecd-b744-7870b4fbc7a6";

    /**
     * Execution identifier for execution
     * @var string
     */
    protected string $executionIdentifier = "c4f935d8-f539-4207-9824-1e6e2da6a211";

    /**
     * @dataProvider dataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfClassInstanceCanBeCreated(array $data): void
    {
        $this->createBaseInstance($data);
    }

    /**
     * @dataProvider dataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfExecutionIdentifierSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data);
        $value = Arr::get($data, 'execution_id');

        $instance->setExecutionIdentifier($value);
        $this->assertSame($value, $instance->getExecutionIdentifier());
    }

    /**
     * @dataProvider dataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfPipelineIdentifierSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data);
        $value = Arr::get($data, 'pipeline_id');

        $instance->setPipelineIdentifier($value);
        $this->assertSame($value, $instance->getPipelineIdentifier());
    }

    /**
     * @dataProvider dataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfTaskIdentifierSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data);
        $value = Arr::get($data, 'task_id');

        $instance->setTaskIdentifier($value);
        $this->assertSame($value, $instance->getTaskIdentifier());
    }

    /**
     * @dataProvider dataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfActionIdentifierSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data);
        $value = Arr::get($data, 'action_id');

        $instance->setActionIdentifier($value);
        $this->assertSame($value, $instance->getActionIdentifier());
    }

    /**
     * @dataProvider dataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfServerIdentifierSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data);
        $value = Arr::get($data, 'server_id');

        $instance->setServerIdentifier($value);
        $this->assertSame($value, $instance->getServerIdentifier());
    }

    /**
     * @dataProvider dataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfCommandSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data);
        $value = Arr::get($data, 'command');

        $instance->setCommand($value);
        $this->assertSame($value, $instance->getCommand());
    }

    /**
     * @dataProvider dataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfArgumentsSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data);
        $value = Arr::get($data, 'arguments');

        $instance->setArguments($value);
        $this->assertSame($value, $instance->getArguments());
    }

    /**
     * @dataProvider dataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfRunAsSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data);
        $value = Arr::get($data, 'run_as');

        $instance->setRunAs($value);
        $this->assertSame($value, $instance->getRunAs());
    }

    /**
     * @dataProvider dataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfWorkingDirectorySetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data);
        $value = Arr::get($data, 'working_dir');

        $instance->setWorkingDirectory($value);
        $this->assertSame($value, $instance->getWorkingDirectory());
    }

    /**
     * @dataProvider dataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfUseSudoSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data);
        $value = Arr::get($data, 'use_sudo');

        $instance->setUseSudo($value);
        $this->assertSame($value, $instance->getUseSudo());
    }

    /**
     * @dataProvider dataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfFailOnErrorSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data);
        $value = Arr::get($data, 'fail_on_error');

        $instance->setFailOnError($value);
        $this->assertSame($value, $instance->getFailOnError());
    }

    /**
     * @dataProvider dataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGenerateStringIdentifierMethod(array $data): void
    {
        $instance = $this->createCompleteInstance($data);
        $this->assertSame(
            $this->generateStreamIdentifier($data),
            $instance->generateStreamIdentifier()
        );
    }

    /**
     * @dataProvider dataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromToTaskArrayMethod(array $data): void
    {
        $instance = $this->createCompleteInstance($data);

        $this->assertSame([
            'execution_id'      =>  Arr::get($data, 'execution_id'),
            'pipeline_id'       =>  Arr::get($data, 'pipeline_id'),
            'task_id'           =>  Arr::get($data, 'task_id'),
            'action_id'         =>  Arr::get($data, 'action_id'),
            'server_id'         =>  Arr::get($data, 'server_id'),
            'command'           =>  Arr::get($data, 'command'),
            'arguments'         =>  Arr::get($data, 'arguments'),
            'working_dir'       =>  Arr::get($data, 'working_dir'),
            'run_as'            =>  Arr::get($data, 'run_as'),
            'use_sudo'          =>  Arr::get($data, 'use_sudo'),
            'fail_on_error'     =>  Arr::get($data, 'fail_on_error'),
        ], $instance->toTaskArray());
    }

    /**
     * @dataProvider dataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetTaskCreationUrlMethod(array $data): void
    {
        $instance = $this->createCompleteInstance($data);
        $this->assertSame(
            $this->generateTaskCreationUrl($data),
            $instance->getTaskCreationUrl()
        );
    }

    /**
     * @dataProvider dataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetTaskStreamUrlMethod(array $data): void
    {
        $instance = $this->createCompleteInstance($data);
        $this->assertSame(
            $this->generateStreamWatchUrl($data),
            $instance->getTaskStreamUrl()
        );
    }

    /**
     * @dataProvider dataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetTaskLogUrlMethod(array $data): void
    {
        $instance = $this->createCompleteInstance($data);
        $this->assertSame(
            $this->generateStreamLoadUrl($data),
            $instance->getTaskLogUrl()
        );
    }

    /**
     * Generate URL for task creation
     * @param array $data
     * @return string
     */
    private function generateTaskCreationUrl(array $data): string
    {
        return sprintf(
            'http://%s:%d/tasks/create',
            Arr::get($data, 'host'),
            Arr::get($data, 'port'),
        );
    }

    /**
     * Generate URL for ongoing stream
     * @param array $data
     * @return string
     */
    private function generateStreamWatchUrl(array $data): string
    {
        return sprintf(
            'http://%s:%d/events/watch?stream=%s',
            Arr::get($data, 'host'),
            Arr::get($data, 'port'),
            $this->generateStreamIdentifier($data)
        );
    }

    /**
     * Generate URL for complete stream logs
     * @param array $data
     * @return string
     */
    private function generateStreamLoadUrl(array $data): string
    {
        return sprintf(
            'http://%s:%d/events/load?stream=%s',
            Arr::get($data, 'host'),
            Arr::get($data, 'port'),
            $this->generateStreamIdentifier($data)
        );
    }

    /**
     * Generate stream identifier
     * @param array $data
     * @return string
     */
    private function generateStreamIdentifier(array $data): string
    {
        return hash('sha256', sprintf(
            '%s_%s_%s_%s_%s',
            Arr::get($data, 'execution_id'),
            Arr::get($data, 'pipeline_id'),
            Arr::get($data, 'task_id'),
            Arr::get($data, 'action_id'),
            Arr::get($data, 'server_id'),
        ));
    }

    /**
     * Create base instance of remote task
     * @param array $data
     * @return RemoteTask
     */
    private function createBaseInstance(array $data): RemoteTask
    {
        $host = (string) Arr::get($data, 'host');
        $port = (int) Arr::get($data, 'port');

        $instance = new RemoteTask($host, $port);
        $this->assertSame($host, $instance->getRunnerHost());
        $this->assertSame($port, $instance->getRunnerPort());

        return $instance;
    }

    /**
     * Create complete instance of remote task with all parameters initialized
     * @param array $data
     * @return RemoteTask
     */
    private function createCompleteInstance(array $data): RemoteTask
    {
        $instance = $this->createBaseInstance($data);
        $instance
            ->setExecutionIdentifier(Arr::get($data, 'execution_id'))
            ->setPipelineIdentifier(Arr::get($data, 'pipeline_id'))
            ->setTaskIdentifier(Arr::get($data, 'task_id'))
            ->setActionIdentifier(Arr::get($data, 'action_id'))
            ->setServerIdentifier(Arr::get($data, 'server_id'))
            ->setCommand(Arr::get($data, 'command'))
            ->setArguments(Arr::get($data, 'arguments'))
            ->setWorkingDirectory(Arr::get($data, 'working_dir'))
            ->setRunAs(Arr::get($data, 'run_as'))
            ->setUseSudo(Arr::get($data, 'use_sudo'))
            ->setFailOnError(Arr::get($data, 'fail_on_error'));
        return $instance;
    }

    /**
     * Data provider
     * @return \array[][]
     */
    public function dataProvider(): array
    {
        return [
            'example_remote_task_entity'        =>  [
                'data'                          =>  [
                    'host'                      =>  '127.0.0.1',
                    'port'                      =>  32175,
                    'execution_id'              =>  $this->executionIdentifier,
                    'pipeline_id'               =>  $this->pipelineIdentifier,
                    'task_id'                   =>  $this->taskIdentifier,
                    'action_id'                 =>  $this->actionIdentifier,
                    'server_id'                 =>  $this->serverIdentifier,
                    'working_dir'               =>  '/home/scripts',
                    'command'                   =>  'php',
                    'arguments'                 =>  [
                        'test.php',
                    ],
                    'run_as'                    =>  'example',
                    'use_sudo'                  =>  false,
                    'fail_on_error'             =>  true,
                ],
            ],
        ];
    }
}
