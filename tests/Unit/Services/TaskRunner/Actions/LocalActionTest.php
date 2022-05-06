<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Actions;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Enums\ActionType;

/**
 * Class LocalActionTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Actions
 */
class LocalActionTest extends AbstractActionTest
{
    /**
     * @dataProvider localActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfClassInstanceCanBeCreated(array $data): void
    {
        $this->createBaseInstance($data, ActionType::LOCAL);
    }


    /**
     * @dataProvider localActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfExecutionIdentifierSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::LOCAL);
        $value = Arr::get($data, 'execution_id');

        $instance->setExecutionIdentifier($value);
        $this->assertSame($value, $instance->getExecutionIdentifier());
    }

    /**
     * @dataProvider localActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfPipelineIdentifierSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::LOCAL);
        $value = Arr::get($data, 'pipeline_id');

        $instance->setPipelineIdentifier($value);
        $this->assertSame($value, $instance->getPipelineIdentifier());
    }

    /**
     * @dataProvider localActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfTaskIdentifierSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::LOCAL);
        $value = Arr::get($data, 'task_id');

        $instance->setTaskIdentifier($value);
        $this->assertSame($value, $instance->getTaskIdentifier());
    }

    /**
     * @dataProvider localActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfActionIdentifierSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::LOCAL);
        $value = Arr::get($data, 'action_id');

        $instance->setActionIdentifier($value);
        $this->assertSame($value, $instance->getActionIdentifier());
    }

    /**
     * @dataProvider localActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfServerIdentifierSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::LOCAL);
        $value = Arr::get($data, 'server_id');

        $instance->setServerIdentifier($value);
        $this->assertSame($value, $instance->getServerIdentifier());
    }

    /**
     * @dataProvider localActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfCommandSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::LOCAL);
        $value = Arr::get($data, 'command');

        $instance->setCommand($value);
        $this->assertSame($value, $instance->getCommand());
    }

    /**
     * @dataProvider localActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfArgumentsSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::LOCAL);
        $value = Arr::get($data, 'arguments');

        $instance->setArguments($value);
        $this->assertSame($value, $instance->getArguments());
    }

    /**
     * @dataProvider localActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfRunAsSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::LOCAL);
        $value = Arr::get($data, 'run_as');

        $instance->setRunAs($value);
        $this->assertSame($value, $instance->getRunAs());
    }

    /**
     * @dataProvider localActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfFailOnErrorSetterAndGetterCanBeUsed(array $data): void
    {
        $instance = $this->createBaseInstance($data, ActionType::LOCAL);
        $value = Arr::get($data, 'fail_on_error');

        $instance->setFailOnError($value);
        $this->assertSame($value, $instance->getFailOnError());
    }


    /**
     * @dataProvider localActionDataProvider
     * @param array $data
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromToActionArrayMethod(array $data): void
    {
        $instance = $this->createCompleteInstance($data, ActionType::LOCAL);

        $this->assertSame([
            'execution_id'      =>  Arr::get($data, 'execution_id'),
            'pipeline_id'       =>  Arr::get($data, 'pipeline_id'),
            'task_id'           =>  Arr::get($data, 'task_id'),
            'action_id'         =>  Arr::get($data, 'action_id'),
            'server_id'         =>  Arr::get($data, 'server_id'),
            'command'           =>  Arr::get($data, 'command'),
            'arguments'         =>  Arr::get($data, 'arguments'),
            'run_as'            =>  Arr::get($data, 'run_as'),
            'fail_on_error'     =>  Arr::get($data, 'fail_on_error'),
            'type'              =>  ActionType::LOCAL,
        ], $instance->toActionArray());
    }
}
