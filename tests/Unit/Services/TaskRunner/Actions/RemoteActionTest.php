<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Actions;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Enums\ActionType;

/**
 * Class RemoteActionTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Actions
 */
class RemoteActionTest extends AbstractActionTest
{
    /**
     * @dataProvider remoteActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfClassInstanceCanBeCreated(array $data): void
    {
        $this->createBaseInstance($data, ActionType::REMOTE);
    }

    /**
     * @dataProvider remoteActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfExecutionIdentifierSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::REMOTE);
        $value = Arr::get($data, 'execution_id');

        $instance->setExecutionIdentifier($value);
        $this->assertSame($value, $instance->getExecutionIdentifier());
    }

    /**
     * @dataProvider remoteActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfPipelineIdentifierSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::REMOTE);
        $value = Arr::get($data, 'pipeline_id');

        $instance->setPipelineIdentifier($value);
        $this->assertSame($value, $instance->getPipelineIdentifier());
    }

    /**
     * @dataProvider remoteActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfTaskIdentifierSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::REMOTE);
        $value = Arr::get($data, 'task_id');

        $instance->setTaskIdentifier($value);
        $this->assertSame($value, $instance->getTaskIdentifier());
    }

    /**
     * @dataProvider remoteActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfActionIdentifierSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::REMOTE);
        $value = Arr::get($data, 'action_id');

        $instance->setActionIdentifier($value);
        $this->assertSame($value, $instance->getActionIdentifier());
    }

    /**
     * @dataProvider remoteActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfServerIdentifierSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::REMOTE);
        $value = Arr::get($data, 'server_id');

        $instance->setServerIdentifier($value);
        $this->assertSame($value, $instance->getServerIdentifier());
    }


    /**
     * @dataProvider remoteActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfCommandSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::REMOTE);
        $value = Arr::get($data, 'command');

        $instance->setCommand($value);
        $this->assertSame($value, $instance->getCommand());
    }

    /**
     * @dataProvider remoteActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfArgumentsSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::REMOTE);
        $value = Arr::get($data, 'arguments');

        $instance->setArguments($value);
        $this->assertSame($value, $instance->getArguments());
    }

    /**
     * @dataProvider remoteActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfRunAsSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::REMOTE);
        $value = Arr::get($data, 'run_as');

        $instance->setRunAs($value);
        $this->assertSame($value, $instance->getRunAs());
    }


    /**
     * @dataProvider remoteActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfWorkingDirectorySetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::REMOTE);
        $value = Arr::get($data, 'working_dir');

        $instance->setWorkingDirectory($value);
        $this->assertSame($value, $instance->getWorkingDirectory());
    }

    /**
     * @dataProvider remoteActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfUseSudoSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::REMOTE);
        $value = Arr::get($data, 'use_sudo');

        $instance->setUseSudo($value);
        $this->assertSame($value, $instance->getUseSudo());
    }


    /**
     * @dataProvider remoteActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfFailOnErrorSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::REMOTE);
        $value = Arr::get($data, 'fail_on_error');

        $instance->setFailOnError($value);
        $this->assertSame($value, $instance->getFailOnError());
    }

    /**
     * @dataProvider remoteActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGenerateStringIdentifierMethod(array $data): void
    {
        $instance = $this->createCompleteInstance($data, ActionType::REMOTE);
        $this->assertSame(
            $this->generateStreamIdentifier($data),
            $instance->generateStreamIdentifier()
        );
    }


    /**
     * @dataProvider remoteActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromToActionArrayMethod(array $data): void
    {
        $instance = $this->createCompleteInstance($data, ActionType::REMOTE);

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
            'type'              =>  ActionType::REMOTE,
        ], $instance->toActionArray());
    }

    /**
     * @dataProvider remoteActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetTaskCreationUrlMethod(array $data): void
    {
        $instance = $this->createCompleteInstance($data, ActionType::REMOTE);
        $this->assertSame(
            $this->generateTaskCreationUrl($data),
            $instance->getTaskCreationUrl()
        );
    }

    /**
     * @dataProvider remoteActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetTaskStreamUrlMethod(array $data): void
    {
        $instance = $this->createCompleteInstance($data, ActionType::REMOTE);
        $this->assertSame(
            $this->generateStreamWatchUrl($data),
            $instance->getTaskStreamUrl()
        );
    }

    /**
     * @dataProvider remoteActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetTaskLogUrlMethod(array $data): void
    {
        $instance = $this->createCompleteInstance($data, ActionType::REMOTE);
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
}
