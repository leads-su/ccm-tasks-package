<?php

namespace ConsulConfigManager\Tasks\Test\backup\Models;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Models\ActionExecutionLog;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionLogInterface;

/**
 * Class ActionExecutionLogTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Models
 */
class ActionExecutionLogTest extends AbstractModelTest
{
    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetIdMethod(array $data): void
    {
        $response = $this->model($data)->getID();
        $this->assertEquals(Arr::get($data, 'id'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetIdMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setID(2);
        $this->assertEquals(2, $model->getID());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetActionExecutionIdMethod(array $data): void
    {
        $response = $this->model($data)->getActionExecutionID();
        $this->assertEquals(Arr::get($data, 'action_execution_id'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetActionExecutionIdMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setActionExecutionID(2);
        $this->assertEquals(2, $model->getActionExecutionID());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetExitCodeMethod(array $data): void
    {
        $response = $this->model($data)->getExitCode();
        $this->assertEquals(Arr::get($data, 'exit_code'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetExitCodeMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setExitCode(2);
        $this->assertEquals(2, $model->getExitCode());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetOutputMethod(array $data): void
    {
        $response = $this->model($data)->getOutput();
        $this->assertEquals(Arr::get($data, 'output'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetOutputMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setOutput([]);
        $this->assertEquals([], $model->getOutput());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromActionExecutionRelation(): void
    {
        $this->createCompletePipeline();
        $log = ActionExecutionLog::first();
        $this->assertInstanceOf(ActionExecutionInterface::class, $log->actionExecution);
    }

    /**
     * Model data provider
     * @return \string[][][]
     */
    public function modelDataProvider(): array
    {
        return $this->actionExecutionLogModelDataProvider();
    }

    /**
     * Create model instance
     * @param array $data
     * @return ActionExecutionLogInterface
     */
    private function model(array $data): ActionExecutionLogInterface
    {
        return $this->actionExecutionLogModel($data);
    }
}
