<?php

namespace ConsulConfigManager\Tasks\Test\Integration;

use Exception;
use ConsulConfigManager\Tasks\Enums\ExecutionState;
use ConsulConfigManager\Tasks\Services\TaskRunner\Runner;
use Illuminate\Contracts\Container\BindingResolutionException;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionInterface;

/**
 * Class RunnerIntegrationTest
 * @package ConsulConfigManager\Tasks\Test\Integration
 */
class RunnerIntegrationTest extends AbstractRunnerIntegrationTest
{
    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function testShouldPassWithModelsAndNoFailingActions(): void
    {
        $this->createCompletePipeline(false, false);
        $this->startRunner();
        $this->assertMatchesForCompletedPipeline();
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function testShouldPassWithModelsAndFailingActions(): void
    {
        $this->createCompletePipeline(false, true);
        $this->startRunner();
        $this->assertMatchesForPartiallyCompletedPipeline();
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function testShouldPassWithRepositoriesAndNoFailingActions(): void
    {
        $this->createCompletePipeline(true, false);
        $this->startRunner();
        $this->assertMatchesForCompletedPipeline();
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function testShouldPassWithRepositoriesAndFailingActions(): void
    {
        $this->createCompletePipeline(true, true);
        $this->startRunner();
        $this->assertMatchesForPartiallyCompletedPipeline();
    }

    /**
     * Create complete pipeline
     * @param bool $useRepository
     * @param bool $hasFailingAction
     * @return void
     * @throws BindingResolutionException|Exception
     */
    private function createCompletePipeline(bool $useRepository = false, bool $hasFailingAction = false): void
    {
        $firstServer = $this->createNewServer(true, $useRepository);
        $secondServer = $this->createNewServer(true, $useRepository);

        $firstAction = $this->createNewAction(false, false, $useRepository);
        $secondAction = $this->createNewAction(false, false, $useRepository);
        $thirdAction = $this->createNewAction(false, false, $useRepository);

        $this->attachServerToAction($firstAction, $firstServer);
        $this->attachServerToAction($firstAction, $secondServer);

        $this->attachServerToAction($secondAction, $firstServer);
        $this->attachServerToAction($secondAction, $secondServer);

        $this->attachServerToAction($thirdAction, $firstServer);
        $this->attachServerToAction($thirdAction, $secondServer);

        $firstTask = $this->createNewTask(false, $useRepository);
        $secondTask = $this->createNewTask(true, $useRepository);
        $thirdTask = $this->createNewTask(false, $useRepository);

        $this->attachActionToTask($firstTask, $firstAction);
        $this->attachActionToTask($firstTask, $secondAction);

        $this->attachActionToTask($secondTask, $firstAction);
        if ($hasFailingAction) {
            $failingAction = $this->createNewAction(true, true, $useRepository);
            $this->attachServerToAction($failingAction, $firstServer);
            $this->attachActionToTask($secondTask, $failingAction);
        }
        $this->attachActionToTask($secondTask, $secondAction);

        $this->attachActionToTask($thirdTask, $thirdAction);

        $pipeline = $this->createNewPipeline($useRepository);
        $this->attachTaskToPipeline($pipeline, $firstTask);
        $this->attachTaskToPipeline($pipeline, $secondTask);
        $this->attachTaskToPipeline($pipeline, $thirdTask);
    }

    /**
     * Start runner
     * @return void
     * @throws BindingResolutionException
     */
    private function startRunner(): void
    {
        $runner = new Runner($this->pipelineIdentifier, false);
        $runner->run();
    }

    /**
     * Assert that pipeline with no failing actions is actually completed
     * @return void
     */
    private function assertMatchesForCompletedPipeline(): void
    {
        $pipelineExecutions = $this->pipelineExecutionRepository()->all();
        $this->assertCount(1, $pipelineExecutions);
        foreach ($pipelineExecutions as $pipelineExecution) {
            $this->assertSame(ExecutionState::SUCCESS, $pipelineExecution->getState());
        }

        $taskExecutions = $this->taskExecutionRepository()->all();
        $this->assertCount(3, $taskExecutions);
        foreach ($taskExecutions as $taskExecution) {
            $this->assertSame(ExecutionState::SUCCESS, $taskExecution->getState());
        }

        $actionExecutions = $this->actionExecutionRepository()->all();
        $this->assertCount(10, $actionExecutions);
        foreach ($actionExecutions as $actionExecution) {
            $this->assertSame(ExecutionState::SUCCESS, $actionExecution->getState());
        }
    }

    /**
     * Assert that pipeline with failing actions is actually completed
     * @return void
     */
    private function assertMatchesForPartiallyCompletedPipeline(): void
    {
        $pipelineExecutions = $this->pipelineExecutionRepository()->all();
        foreach ($pipelineExecutions as $pipelineExecution) {
            $this->assertSame(ExecutionState::PARTIALLY_COMPLETED, $pipelineExecution->getState());
        }

        $taskExecutions = $this->taskExecutionRepository()->all();
        $this->assertCount(count($this->taskActionReference), $taskExecutions);

        /**
         * @var TaskExecutionInterface $taskExecution
         */
        foreach ($taskExecutions as $taskExecution) {
            $taskIdentifier = $taskExecution->getTaskUuid();

            $taskActions = $this->taskActionReference[$taskIdentifier];
            $taskActionsCount = count($taskActions);
            $serversCount = count($this->serverIdentifiers);

            $failingTask = false;
            foreach ($this->failingActionIdentifiers as $identifier) {
                if (in_array($identifier, $taskActions)) {
                    $failingTask = true;
                    break;
                }
            }

            if ($failingTask) {
                $actionExecutionsCount = ($taskActionsCount * $serversCount) - 1;
            } else {
                $actionExecutionsCount = $taskActionsCount * $serversCount;
            }

            $actionExecutions = $this->actionExecutionRepository()
                ->findManyBy('task_uuid', $taskIdentifier);
            $this->assertCount($actionExecutionsCount, $actionExecutions);
        }
    }

    /**
     * Method used to display created pipeline in more readable fashion
     * @return void
     */
    private function debug(): void
    {
        dd([
            'pipeline_identifier'           =>  $this->pipelineRepository()->findBy('uuid', $this->pipelineIdentifier)->toArray(),
            'server_addresses'              =>  $this->serverAddresses,
            'server_identifiers'            =>  array_map(function (string $uuid): array {
                return $this->serverRepository()->findBy('uuid', $uuid)->toArray();
            }, $this->serverIdentifiers),
            'action_identifiers'            =>  array_map(function (string $uuid): array {
                return $this->actionRepository()->findBy('uuid', $uuid)->toArray();
            }, $this->actionIdentifiers),
            'task_identifiers'              =>  array_map(function (string $uuid): array {
                return $this->taskRepository()->findBy('uuid', $uuid)->toArray();
            }, $this->taskIdentifiers),
            'action_server_reference'       =>  $this->actionServerReference,
            'failing_action_identifiers'    =>  array_map(function (string $uuid): array {
                return $this->actionRepository()->findBy('uuid', $uuid)->toArray();
            }, $this->failingActionIdentifiers),
            'failing_action_servers'        =>  $this->failingActionServers,
            'task_action_reference'         =>  $this->taskActionReference,
            'pipeline_task_reference'       =>  $this->pipelineTaskReference,
        ]);
    }
}
