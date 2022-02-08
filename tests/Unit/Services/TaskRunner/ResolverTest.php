<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner;

use ReflectionException;
use Illuminate\Support\Facades\DB;
use ConsulConfigManager\Tasks\Test\TestCase;
use Illuminate\Database\Eloquent\Collection;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Tasks\Services\TaskRunner\Resolver;
use Illuminate\Contracts\Container\BindingResolutionException;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionInterface;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\PipelineEntity;
use ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Entities\AbstractEntityTest;

/**
 * Class ResolverTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner
 */
class ResolverTest extends AbstractEntityTest
{
    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function testShouldPassIfInstanceCanBeCreated(): void
    {
        $instance = $this->createInstance();
        $this->assertInstanceOf(Resolver::class, $instance);
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function testShouldPassIfResolvePipelineMethodIsUsable(): void
    {
        $instance = $this->createInstance();
        $result = TestCase::callMethod($instance, 'resolvePipeline');
        $this->assertInstanceOf(PipelineInterface::class, $result);
        $this->assertSamePipeline($result);
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function testShouldPassIfResolvePipelineTasksMethodIsUsable(): void
    {
        $instance = $this->createInstance();
        $result = TestCase::callMethod($instance, 'resolvePipelineTasks', [$this->getPipelineInstance()]);
        $this->assertCount(1, $result);
        foreach ($result as $task) {
            $this->assertSameTask($task);
        }
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function testShouldPassIfResolveTaskActionsMethodIsUsable(): void
    {
        $instance = $this->createInstance();
        $result = TestCase::callMethod($instance, 'resolveTaskActions', [$this->getTaskInstance()]);
        $this->assertCount(1, $result);
        foreach ($result as $action) {
            $this->assertSameAction($action);
        }
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function testShouldPassIfResolveActionServersMethodIsUsable(): void
    {
        $instance = $this->createInstance();
        $result = TestCase::callMethod($instance, 'resolveActionServers', [$this->getActionInstance()]);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(Collection::class, $result);
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function testShouldPassIfCreateNewPipelineExecutionMethodIsUsable(): void
    {
        $instance = $this->createInstance();
        DB::table('pipeline_executions')->truncate();

        $result = TestCase::callMethod($instance, 'createNewPipelineExecution', [
            $this->pipelineIdentifier,
        ]);

        $this->assertInstanceOf(PipelineExecutionInterface::class, $result);
        $this->assertSamePipelineExecution($result);
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function testShouldPassIfCreateNewTaskExecutionMethodIsUsable(): void
    {
        $instance = $this->createInstance();
        DB::table('task_executions')->truncate();

        $result = TestCase::callMethod($instance, 'createNewTaskExecution', [
            $this->taskIdentifier,
            $this->pipelineIdentifier,
            $this->executionIdentifier,
        ]);
        $this->assertInstanceOf(TaskExecutionInterface::class, $result);
        $this->assertSameTaskExecution($result);
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function testShouldPassIfCreateNewActionExecutionMethodIsUsable(): void
    {
        $instance = $this->createInstance();
        DB::table('action_executions')->truncate();

        $result = TestCase::callMethod($instance, 'createNewActionExecution', [
            $this->serverIdentifier,
            $this->actionIdentifier,
            $this->taskIdentifier,
            $this->pipelineIdentifier,
            $this->executionIdentifier,
        ]);
        $this->assertInstanceOf(ActionExecutionInterface::class, $result);
        $this->assertSameActionExecution($result);
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function testShouldPassIfGetPipelineEntityIsUsable(): void
    {
        $instance = $this->createInstance();
        $this->assertInstanceOf(PipelineEntity::class, $instance->getPipelineEntity());
    }

    /**
     * Create new resolver instance
     * @return Resolver
     * @throws BindingResolutionException
     */
    private function createInstance(): Resolver
    {
        $this->createAndGetServer();
        $this->createAndGetAction();
        $this->attachServerToAction();

        $this->createAndGetTask();
        $this->attachActionToTask();

        $this->createAndGetPipeline();
        $this->attachTaskToPipeline();

        $instance = new Resolver($this->pipelineIdentifier);
        $instance->bootstrap();
        return $instance;
    }
}
