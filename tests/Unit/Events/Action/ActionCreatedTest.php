<?php

namespace ConsulConfigManager\Tasks\Test\backup\Events\Action;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Events\Action\ActionCreated;

/**
 * Class ActionCreatedTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Events\Action
 */
class ActionCreatedTest extends AbstractActionEventTest
{
    /**
     * @inheritDoc
     */
    protected string $activeEventHandler = ActionCreated::class;

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfEventCanBeCreated(array $data): void
    {
        $this->assertInstanceOf(ActionCreated::class, $this->createClassInstance($data));
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfValidDataReturnedFromGetNameMethod(array $data): void
    {
        $this->assertEquals(Arr::get($data, 'name'), $this->createClassInstance($data)->getName());
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfValidDataReturnedFromGetDescriptionMethod(array $data): void
    {
        $this->assertEquals(Arr::get($data, 'description'), $this->createClassInstance($data)->getDescription());
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfValidDataReturnedFromGetTypeMethod(array $data): void
    {
        $this->assertEquals(Arr::get($data, 'type'), $this->createClassInstance($data)->getType());
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfValidDataReturnedFromGetCommandMethod(array $data): void
    {
        $this->assertEquals(Arr::get($data, 'command'), $this->createClassInstance($data)->getCommand());
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfValidDataReturnedFromGetArgumentsMethod(array $data): void
    {
        $this->assertEquals(Arr::get($data, 'arguments'), $this->createClassInstance($data)->getArguments());
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfValidDataReturnedFromGetWorkingDirectoryMethod(array $data): void
    {
        $this->assertEquals(Arr::get($data, 'working_dir'), $this->createClassInstance($data)->getWorkingDirectory());
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfValidDataReturnedFromGetRunAsMethod(array $data): void
    {
        $this->assertEquals(Arr::get($data, 'run_as'), $this->createClassInstance($data)->getRunAs());
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfValidDataReturnedFromIsUsingSudo(array $data): void
    {
        $this->assertEquals(Arr::get($data, 'use_sudo'), $this->createClassInstance($data)->isUsingSudo());
    }

    /**
     * @param array $data
     *
     * @return void
     * @dataProvider eventDataProvider
     */
    public function testShouldPassIfValidDataReturnedFromIsFailingOnError(array $data): void
    {
        $this->assertEquals(Arr::get($data, 'fail_on_error'), $this->createClassInstance($data)->isFailingOnError());
    }

    /**
     * @inheritDoc
     * @param array $data
     * @return ActionCreated
     */
    protected function createClassInstance(array $data): ActionCreated
    {
        return new $this->activeEventHandler(
            Arr::get($data, 'name'),
            Arr::get($data, 'description'),
            Arr::get($data, 'type'),
            Arr::get($data, 'command'),
            Arr::get($data, 'arguments'),
            Arr::get($data, 'working_dir'),
            Arr::get($data, 'run_as'),
            Arr::get($data, 'use_sudo'),
            Arr::get($data, 'fail_on_error'),
        );
    }
}
