<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Actions;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\Enums\ActionType;
use ConsulConfigManager\Tasks\Services\TaskRunner\Actions\LocalAction;
use ConsulConfigManager\Tasks\Services\TaskRunner\Actions\RemoteAction;
use ConsulConfigManager\Tasks\Services\TaskRunner\Actions\AbstractAction;

/**
 * Class AbstractActionTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Actions
 */
abstract class AbstractActionTest extends TestCase
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
     * Create base instance of action runner
     * @param array $data
     * @param int $type
     * @return AbstractAction
     */
    protected function createBaseInstance(array $data, int $type = ActionType::REMOTE): AbstractAction
    {
        if ($type === ActionType::REMOTE) {
            $host = (string) Arr::get($data, 'host');
            $port = (int) Arr::get($data, 'port');

            $instance = new RemoteAction($host, $port);
            $this->assertInstanceOf(RemoteAction::class, $instance);
            $this->assertSame($host, $instance->getRunnerHost());
            $this->assertSame($port, $instance->getRunnerPort());
        } else {
            $instance = new LocalAction();
            $this->assertInstanceOf(LocalAction::class, $instance);
        }
        return $instance;
    }

    /**
     * Create complete action runner instance
     * @param array $data
     * @param int $type
     * @return AbstractAction
     */
    protected function createCompleteInstance(array $data, int $type = ActionType::REMOTE): AbstractAction
    {
        $instance = $this->createBaseInstance($data, $type);
        $instance
            ->setExecutionIdentifier(Arr::get($data, 'execution_id'))
            ->setPipelineIdentifier(Arr::get($data, 'pipeline_id'))
            ->setTaskIdentifier(Arr::get($data, 'task_id'))
            ->setActionIdentifier(Arr::get($data, 'action_id'))
            ->setServerIdentifier(Arr::get($data, 'server_id'))
            ->setCommand(Arr::get($data, 'command'))
            ->setArguments(Arr::get($data, 'arguments'))
            ->setRunAs(Arr::get($data, 'run_as'))
            ->setFailOnError(Arr::get($data, 'fail_on_error'));

        if ($type === ActionType::REMOTE) {
            $instance->setWorkingDirectory(Arr::get($data, 'working_dir'))
                ->setRunAs(Arr::get($data, 'run_as'))
                ->setUseSudo(Arr::get($data, 'use_sudo'));
        }

        return $instance;
    }

    /**
     * Remote action data provider
     * @return \array[][]
     */
    public function remoteActionDataProvider(): array
    {
        return [
            'example_remote_action_entity'      =>  [
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

    /**
     * Local action data provider
     * @return \array[][]
     */
    public function localActionDataProvider(): array
    {
        return [
            'example_local_action_entity'       =>  [
                'data'                          =>  [
                    'execution_id'              =>  $this->executionIdentifier,
                    'pipeline_id'               =>  $this->pipelineIdentifier,
                    'task_id'                   =>  $this->taskIdentifier,
                    'action_id'                 =>  $this->actionIdentifier,
                    'server_id'                 =>  $this->serverIdentifier,
                    'command'                   =>  'https://google.com',
                    'arguments'                 =>  [
                        '--head',
                    ],
                    'run_as'                    =>  'curl',
                    'fail_on_error'             =>  true,
                ],
            ],
        ];
    }
}
