<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Models;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Models\ActionExecution;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Consul\Agent\Interfaces\ServiceInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionLogInterface;

/**
 * Class ActionExecutionTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Models
 */
class ActionExecutionTest extends AbstractModelTest
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
    public function testShouldPassIfValidDataReturnedFromGetServerUuidMethod(array $data): void
    {
        $response = $this->model($data)->getServerUuid();
        $this->assertEquals(Arr::get($data, 'server_uuid'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetServerUuidMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setServerUuid('1c64f76a-84d3-4196-a0bf-3f3bef6d05da');
        $this->assertEquals('1c64f76a-84d3-4196-a0bf-3f3bef6d05da', $model->getServerUuid());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetActionUuidMethod(array $data): void
    {
        $response = $this->model($data)->getActionUuid();
        $this->assertEquals(Arr::get($data, 'action_uuid'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetActionUuidMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setActionUuid('1c64f76a-84d3-4196-a0bf-3f3bef6d05da');
        $this->assertEquals('1c64f76a-84d3-4196-a0bf-3f3bef6d05da', $model->getActionUuid());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetTaskUuidMethod(array $data): void
    {
        $response = $this->model($data)->getTaskUuid();
        $this->assertEquals(Arr::get($data, 'task_uuid'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetTaskUuidMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setTaskUuid('1c64f76a-84d3-4196-a0bf-3f3bef6d05da');
        $this->assertEquals('1c64f76a-84d3-4196-a0bf-3f3bef6d05da', $model->getTaskUuid());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetPipelineUuidMethod(array $data): void
    {
        $response = $this->model($data)->getPipelineUuid();
        $this->assertEquals(Arr::get($data, 'pipeline_uuid'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetPipelineUuidMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setPipelineUuid('1c64f76a-84d3-4196-a0bf-3f3bef6d05da');
        $this->assertEquals('1c64f76a-84d3-4196-a0bf-3f3bef6d05da', $model->getPipelineUuid());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetPipelineExecutionUuidMethod(array $data): void
    {
        $response = $this->model($data)->getPipelineExecutionUuid();
        $this->assertEquals(Arr::get($data, 'pipeline_execution_uuid'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetPipelineExecutionUuidMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setPipelineExecutionUuid('1c64f76a-84d3-4196-a0bf-3f3bef6d05da');
        $this->assertEquals('1c64f76a-84d3-4196-a0bf-3f3bef6d05da', $model->getPipelineExecutionUuid());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetStateMethod(array $data): void
    {
        $response = $this->model($data)->getState();
        $this->assertEquals(Arr::get($data, 'state'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetStateMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setState(2);
        $this->assertEquals(2, $model->getState());
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromActionRelation(): void
    {
        $this->createCompletePipeline();
        $entity = ActionExecution::where('action_uuid', '=', self::$actionUUID)->first();
        $this->assertInstanceOf(ActionInterface::class, $entity->action);
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromTaskRelation(): void
    {
        $this->createCompletePipeline();
        $entity = ActionExecution::where('action_uuid', '=', self::$actionUUID)->first();
        $this->assertInstanceOf(TaskInterface::class, $entity->task);
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromPipelineRelation(): void
    {
        $this->createCompletePipeline();
        $entity = ActionExecution::where('action_uuid', '=', self::$actionUUID)->first();
        $this->assertInstanceOf(PipelineInterface::class, $entity->pipeline);
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromServerRelation(): void
    {
        $this->createCompletePipeline();
        $entity = ActionExecution::where('action_uuid', '=', self::$actionUUID)->first();
        $this->assertInstanceOf(ServiceInterface::class, $entity->server);
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromPipelineExecutionRelation(): void
    {
        $this->createCompletePipeline();
        $entity = ActionExecution::where('action_uuid', '=', self::$actionUUID)->first();
        $this->assertInstanceOf(PipelineExecutionInterface::class, $entity->pipelineExecution);
    }

    /**
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromLogRelation(): void
    {
        $this->createCompletePipeline();
        $execution = ActionExecution::where('action_uuid', '=', self::$actionUUID)->first();
        $this->assertInstanceOf(ActionExecutionLogInterface::class, $execution->log);
    }

    /**
     * Model data provider
     * @return \string[][][]
     */
    public function modelDataProvider(): array
    {
        return $this->actionExecutionModelDataProvider();
    }

    /**
     * Create model instance
     * @param array $data
     * @return ActionExecutionInterface
     */
    private function model(array $data): ActionExecutionInterface
    {
        return $this->actionExecutionModel($data);
    }
}
