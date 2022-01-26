<?php

namespace ConsulConfigManager\Tasks\Test\Integration;

use ConsulConfigManager\Tasks\Enums\ExecutionState;
use ConsulConfigManager\Tasks\Services\TaskRunner\Runner;
use Illuminate\Contracts\Container\BindingResolutionException;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionInterface;

/**
 * Class RunnerIntegrationTest
 * @package ConsulConfigManager\Tasks\Test\Integration
 */
class RunnerIntegrationTest extends AbstractRunnerIntegrationTest
{
//    /**
//     * @return void
//     * @throws BindingResolutionException
//     */
//    public function testShouldPassIfCanCreateBasePipelineItemsThroughModels(): void
//    {
//        $this->createCompletePipeline(false, false);
//        $this->startRunner();
//        $this->assertMatchesForCompletedPipeline();
//    }

//    /**
//     * @return void
//     * @throws BindingResolutionException
//     */
//    public function testShouldPassIfCanCreateBasePipelineItemsThroughRepositories(): void
//    {
//        $this->createCompletePipeline(true, false);
//        $this->startRunner();
//        $this->assertMatchesForCompletedPipeline();
//    }
//
//    /**
//     * @return void
//     * @throws BindingResolutionException
//     */
//    public function testShouldPassIfCanCreateBasePipelineItemsThroughModelsWithFailing(): void
//    {
//        $this->createCompletePipeline(false, true);
//        $this->startRunner();
//        $this->assertMatchesForPartiallyCompletedPipeline();
//
//        $data = [];
//
//        foreach (PipelineExecution::all() as $pipeline) {
//            $data[$pipeline->getUuid()] = [
//                'pipeline'      =>  $pipeline->toArray(),
//                'tasks'         =>  [],
//            ];
//        }
//
//        foreach (TaskExecution::all() as $task) {
//            $data[$task->getPipelineExecutionUuid()]['tasks'][$task->getTaskUuid()] = [
//                'task'      =>  $task->toArray(),
//                'actions'   =>  [],
//            ];
//        }
//
//        foreach (ActionExecution::all() as $action) {
//            $data[$action->getPipelineExecutionUuid()]['tasks'][$action->getTaskUuid()]['actions'][$action->getActionUuid()] = $action->toArray();
//        }
//
//    }
//
//    /**
//     * @return void
//     * @throws BindingResolutionException
//     */
//    public function testShouldPassIfCanCreateBasePipelineItemsThroughRepositoriesWithFailing(): void
//    {
//        $this->createCompletePipeline(true, true);
//        $this->startRunner();
//    }

    /**
     * Start runner
     * @return void
     * @throws BindingResolutionException
     */
    private function startRunner(): void
    {
        $runner = new Runner($this->pipelineIdentifier);
        $runner->run();
    }

    private function assertMatchesForCompletedPipeline(): void
    {
        $pipelineExecutions = $this->pipelineExecutionRepository()->all();
        $this->assertCount(1, $pipelineExecutions);
        foreach ($pipelineExecutions as $pipelineExecution) {
            $this->assertSame(ExecutionState::SUCCESS, $pipelineExecution->getState());
        }

        $taskExecutions = $this->taskExecutionRepository()->all();
        $this->assertCount(2, $taskExecutions);
        foreach ($taskExecutions as $taskExecution) {
            $this->assertSame(ExecutionState::SUCCESS, $taskExecution->getState());
        }

        $actionExecutions = $this->actionExecutionRepository()->all();
        $this->assertCount(6, $actionExecutions);
        foreach ($actionExecutions as $actionExecution) {
            $this->assertSame(ExecutionState::SUCCESS, $actionExecution->getState());
        }
    }

    private function assertMatchesForPartiallyCompletedPipeline(): void
    {
        $pipelineExecutions = $this->pipelineExecutionRepository()->all();
        foreach ($pipelineExecutions as $pipelineExecution) {
            $this->assertSame(ExecutionState::PARTIALLY_COMPLETED, $pipelineExecution->getState());
        }

        $taskExecutions = $this->taskExecutionRepository()->all();
        /**
         * @var TaskExecutionInterface $taskExecution
         */
        foreach ($taskExecutions as $taskExecution) {
            $identifier = $taskExecution->getTaskUuid();
            switch ($identifier) {
                case $this->firstSuccessfulTaskIdentifier:
                    $this->assertSame(ExecutionState::SUCCESS, $taskExecution->getState());
                    break;
                case $this->secondSuccessfulTaskIdentifier:
                    $this->assertSame(ExecutionState::CANCELED, $taskExecution->getState());
                    break;
                case $this->failingTaskIdentifier:
                    $this->assertSame(ExecutionState::PARTIALLY_COMPLETED, $taskExecution->getState());
                    break;
            }
        }

        $actionExecutions = $this->actionExecutionRepository()->all();

        $index = 0;
        /**
         * @var ActionExecutionInterface $actionExecution
         */
        foreach ($actionExecutions as $actionExecution) {
            if ($actionExecution->getTaskUuid() === $this->failingTaskIdentifier) {
                $state = 0;
                switch ($index) {
                    case 0:
                        $state = ExecutionState::SUCCESS;
                        break;
                    case 1:
                        $state = ExecutionState::FAILURE;
                        break;
                    case 2:
                        $state = ExecutionState::CANCELED;
                        break;
                }
                $this->assertSame($state, $taskExecution->getState());
                $index++;
            } else {
                $index = 0;

                if ($actionExecution->getTaskUuid() === $this->secondSuccessfulTaskIdentifier) {
                    $this->assertSame(ExecutionState::CANCELED, $actionExecution->getState());
                } else {
                    $this->assertSame(ExecutionState::SUCCESS, $taskExecution->getState());
                }
            }
        }
    }

    /**
     * @param bool $repository
     * @param bool $withFailing
     * @return void
     * @throws BindingResolutionException
     */
    private function createCompletePipeline(bool $repository = false, bool $withFailing = false): void
    {
        $pipeline = $this->createPipeline($repository);

        $firstTask = $this->createFirstSuccessfulTask($repository);
        $secondTask = $this->createSecondSuccessfulTask($repository);
        $failingTask = $this->createFailingTask($repository);


        if ($withFailing) {
            $this->bindPipelineAndTask($pipeline, $firstTask, 1);
            $this->bindPipelineAndTask($pipeline, $failingTask, 2);
            $this->bindPipelineAndTask($pipeline, $secondTask, 3);
        } else {
            $this->bindPipelineAndTask($pipeline, $firstTask, 1);
            $this->bindPipelineAndTask($pipeline, $secondTask, 2);
        }

        $firstAction = $this->createFirstSuccessfulAction($repository);
        $secondAction = $this->createSecondSuccessfulAction($repository);
        $failingAction = $this->createFailingAction($repository);

        $this->bindTaskAndAction($firstTask, $firstAction, 1);
        $this->bindTaskAndAction($firstTask, $secondAction, 2);
        $this->bindTaskAndAction($secondTask, $firstAction, 1);

        if ($withFailing) {
            $this->bindTaskAndAction($failingTask, $firstAction, 1);
            $this->bindTaskAndAction($failingTask, $failingAction, 2);
            $this->bindTaskAndAction($failingTask, $secondAction, 3);
        }

        $firstServer = $this->createFirstServer($repository);
//        $secondServer = $this->createSecondServer($repository);

        $this->bindActionAndServer($firstAction, $firstServer);
//        $this->bindActionAndServer($firstAction, $secondServer);
        $this->bindActionAndServer($secondAction, $firstServer);
//        $this->bindActionAndServer($secondAction, $secondServer);

        if ($withFailing) {
            $this->bindActionAndServer($failingAction, $firstServer);
        }
    }
}
