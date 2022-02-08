<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner;

use ReflectionException;
use Illuminate\Console\OutputStyle;
use ConsulConfigManager\Tasks\Test\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use ConsulConfigManager\Tasks\Enums\ExecutionState;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\BufferedOutput;
use ConsulConfigManager\Tasks\Services\TaskRunner\Handler;
use ConsulConfigManager\Tasks\Services\TaskRunner\LoggableClass;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\TaskEntity;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\ActionEntity;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\ServerEntity;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\PipelineEntity;
use ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner\Entities\AbstractEntityTest;

/**
 * Class HandlerTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner
 */
class HandlerTest extends AbstractEntityTest
{
    /**
     * Buffered Output interface instance
     * @var BufferedOutput|null
     */
    private ?BufferedOutput $output = null;

    /**
     * @return void
     */
    public function testShouldPassIfInstanceCanBeCreated(): void
    {
        $instance = $this->createInstance();
        $this->assertInstanceOf(Handler::class, $instance);
    }

    /**
     * @return void
     */
    public function testShouldPassIfMethodEnableDebugMethodIsUsable(): void
    {
        $instance = $this->createInstance();
        $this->assertFalse($instance->getDebug());
        $instance->enableDebug();
        $this->assertTrue($instance->getDebug());
    }

    /**
     * @return void
     */
    public function testShouldPassIfMethodDisableDebugMethodIsUsable(): void
    {
        $instance = $this->createInstance();
        $instance->enableDebug();
        $this->assertTrue($instance->getDebug());
        $instance->disableDebug();
        $this->assertFalse($instance->getDebug());
    }

    /**
     * @return void
     */
    public function testShouldPassIfMethodGetDebugMethodIsUsable(): void
    {
        $instance = $this->createInstance();
        $this->assertFalse($instance->getDebug());
    }

    /**
     * @return void
     */
    public function testShouldPassIfMethodSetDebugMethodIsUsable(): void
    {
        $instance = $this->createInstance();
        $this->assertFalse($instance->getDebug());
        $instance->setDebug(true);
        $this->assertTrue($instance->getDebug());
    }

    /**
     * @return void
     */
    public function testShouldPassIfIsDebugMethodIsUsable(): void
    {
        $instance = $this->createInstance();
        $this->assertFalse($instance->isDebug());
        $instance->setDebug(true);
        $this->assertTrue($instance->isDebug());
    }

    /**
     * @return void
     */
    public function testShouldPassIfGetOutputInterfaceMethodCanReturnNull(): void
    {
        $instance = $this->createInstance();
        $this->assertNull($instance->getOutputInterface());
    }

    /**
     * @return void
     */
    public function testShouldPassIfSetOutputInterfaceMethodCanAcceptNull(): void
    {
        $instance = $this->createInstance();
        $this->assertNull($instance->getOutputInterface());
        $this->assertInstanceOf(LoggableClass::class, $instance->setOutputInterface(null));
        $this->assertNull($instance->getOutputInterface());
    }

    /**
     * @return void
     */
    public function testShouldPassIfGetSetOutputInterfaceMethodsAreUsable(): void
    {
        $output = new OutputStyle(new ArrayInput([]), new ConsoleOutput());
        $instance = $this->createInstance();
        $this->assertNull($instance->getOutputInterface());
        $this->assertInstanceOf(LoggableClass::class, $instance->setOutputInterface($output));
        $this->assertInstanceOf(OutputStyle::class, $instance->getOutputInterface());
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testShouldPassIfHasOutputInterfaceMethodReturnsFalseWhenNotSet(): void
    {
        $instance = $this->createInstance();
        $this->assertFalse(TestCase::callMethod($instance, 'hasOutputInterface'));
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testShouldPassIfHasOutputInterfaceMethodReturnsTrueWhenSet(): void
    {
        $instance = $this->createInstance(true);
        $this->assertTrue(TestCase::callMethod($instance, 'hasOutputInterface'));
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testShouldPassIfDebugNewLineMethodIsUsable(): void
    {
        $instance = $this->createInstance(true, true);
        TestCase::callMethod($instance, 'debugNewLine');
        $this->assertSame(PHP_EOL, $this->output->fetch());
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testShouldPassIfDebugNewLineMethodWithMultipleIsUsable(): void
    {
        $instance = $this->createInstance(true, true);
        TestCase::callMethod($instance, 'debugNewLine', [5]);
        $this->assertSame(str_repeat(PHP_EOL, 5), $this->output->fetch());
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testShouldPassIfDebugInfoMethodIsUsable(): void
    {
        $instance = $this->createInstance(true, true);
        TestCase::callMethod($instance, 'debugInfo', ['Hello World']);
        $this->assertSame('Hello World' . PHP_EOL, $this->output->fetch());
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testShouldPassIfDebugCommentMethodIsUsable(): void
    {
        $instance = $this->createInstance(true, true);
        TestCase::callMethod($instance, 'debugComment', ['Hello World']);
        $this->assertSame('Hello World' . PHP_EOL, $this->output->fetch());
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testShouldPassIfDebugErrorMethodIsUsable(): void
    {
        $instance = $this->createInstance(true, true);
        TestCase::callMethod($instance, 'debugError', ['Hello World']);
        $this->assertSame('Hello World' . PHP_EOL, $this->output->fetch());
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testShouldPassIfDebugWarnMethodIsUsable(): void
    {
        $instance = $this->createInstance(true, true);
        TestCase::callMethod($instance, 'debugWarn', ['Hello World']);
        $this->assertSame('Hello World' . PHP_EOL, $this->output->fetch());
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testShouldPassIfDebugLineMethodIsUsable(): void
    {
        $instance = $this->createInstance(true, true);
        TestCase::callMethod($instance, 'debugLine', ['Hello World']);
        $this->assertSame('Hello World' . PHP_EOL, $this->output->fetch());
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testShouldPassIfDebugLineDoesNothingIfDebugDisabled(): void
    {
        $instance = $this->createInstance(true, false);
        TestCase::callMethod($instance, 'debugLine', ['Hello World']);
        $this->assertSame('', $this->output->fetch());
    }

    /**
     * Create new handler instance
     * @param bool $addOutput
     * @param bool $enableDebug
     * @return Handler
     */
    private function createInstance(bool $addOutput = false, bool $enableDebug = false): Handler
    {
        $instance = new Handler($this->createPipelineEntityInstance(true));
        $instance->setDebug($enableDebug);
        if ($addOutput) {
            $this->output = new BufferedOutput();
            $output = new OutputStyle(new ArrayInput([]), $this->output);
            $instance->setOutputInterface($output);
        }
        $instance->bootstrap();
        return $instance;
    }

    /**
     * Create new pipeline entity instance
     * @param bool $addTask
     * @param int $executionState
     * @return PipelineEntity
     */
    private function createPipelineEntityInstance(bool $addTask = false, int $executionState = ExecutionState::CREATED): PipelineEntity
    {
        $this->createAndGetPipeline();
        $instance = new PipelineEntity($this->createAndGetPipelineExecution());

        if ($addTask) {
            $instance->bootstrap();
            $instance->addTask(
                $this->createTaskEntityInstance($executionState)
            );
        }

        return $instance;
    }

    /**
     * Create new task entity instance
     * @param int $executionState
     * @return TaskEntity
     */
    private function createTaskEntityInstance(int $executionState = ExecutionState::CREATED): TaskEntity
    {
        $this->createAndGetTask();
        $instance = new TaskEntity(
            $this->createAndGetTaskExecution($executionState)
        );

        $instance->bootstrap();
        $instance->addServer(
            $this->createServerEntityInstance($executionState)
        );

        return $instance;
    }

    /**
     * Create new server entity instance
     * @param int $actionState
     * @return ServerEntity
     */
    private function createServerEntityInstance(int $actionState = ExecutionState::CREATED): ServerEntity
    {
        $this->createAndGetServer();
        $instance = new ServerEntity($this->serverIdentifier);
        $instance->bootstrap();

        $instance->addAction(
            $this->createActionEntityInstance($actionState)
        );

        return $instance;
    }

    /**
     * Create new action entity instance
     * @param int $state
     * @return ActionEntity
     */
    private function createActionEntityInstance(int $state = ExecutionState::CREATED): ActionEntity
    {
        $this->createAndGetAction();
        $actionEntity = new ActionEntity(
            $this->createAndGetActionExecution($state),
            $this->createAndGetRemoteTask()
        );
        $actionEntity->bootstrap();
        return $actionEntity;
    }
}
