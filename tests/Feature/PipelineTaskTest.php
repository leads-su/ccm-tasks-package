<?php

namespace ConsulConfigManager\Tasks\Test\Feature;

use Illuminate\Support\Str;
use ConsulConfigManager\Tasks\Models\Task;
use ConsulConfigManager\Tasks\Models\Pipeline;
use ConsulConfigManager\Tasks\Models\PipelineTask;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskInterface;

/**
 * Class PipelineTaskTest
 * @package ConsulConfigManager\Tasks\Test\Feature
 */
class PipelineTaskTest extends AbstractFeatureTest
{
    /**
     * @return void
     */
    public function testShouldPassIfEmptyTasksListCanBeRetrieved(): void
    {
        $this->createAndGetPipeline();
        $response = $this->get('/task-manager/pipelines/' . $this->getPipelineIdentifier() . '/tasks');
        $response->assertExactJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  [],
            'message'       =>  'Successfully fetched list of tasks for requested pipeline',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundReturnedOnNonExistentPipeline(): void
    {
        $response = $this->get('/task-manager/pipelines/1/tasks');
        $response->assertExactJson([
            'success'       =>  false,
            'code'          =>  404,
            'data'          =>  [],
            'message'       =>  'Unable to find requested pipeline',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfPipelineTaskCanBeCreatedWithUuid(): void
    {
        $response = $this->createAndGetPipelineTask();
        $response->assertJson([
            'success'           =>  true,
            'code'              =>  201,
            'data'              =>  [
                'task_uuid'     =>  $this->getTaskIdentifier(),
                'deleted_at'    =>  null,
                'order'         =>  1,
                'pipeline_uuid' =>  $this->getPipelineIdentifier(),
            ],
            'message'           =>  'Successfully created new pipeline task',
        ]);
        $this->assertDatabaseCount('pipeline_tasks', 1);
    }

    /**
     * @return void
     */
    public function testShouldPassIfPipelineTaskCanBeCreatedWithId(): void
    {
        $response = $this->createAndGetPipelineTaskWithIDs();
        $response->assertJson([
            'success'           =>  true,
            'code'              =>  201,
            'data'              =>  [
                'task_uuid'     =>  $this->getTaskIdentifier(),
                'deleted_at'    =>  null,
                'order'         =>  1,
                'pipeline_uuid' =>  $this->getPipelineIdentifier(),
            ],
            'message'           =>  'Successfully created new pipeline task',
        ]);
        $this->assertDatabaseCount('pipeline_tasks', 1);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNonEmptyTasksListCanBeRetrieved(): void
    {
        $this->createAndGetPipelineTask();

        $response = $this->get('/task-manager/pipelines/' . $this->getPipelineIdentifier() . '/tasks');
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToCreatePipelineTaskWithInvalidPipeline(): void
    {
        $response = $this->createAndGetPipelineTaskWithIDs(failPipelineCreation: true);
        $response->assertStatus(404);
        $response->assertExactJson([
            'success'       => false,
            'code'          => 404,
            'data'          => [],
            'message'       => 'Unable to find requested pipeline',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToCreatePipelineTaskWithInvalidTask(): void
    {
        $response = $this->createAndGetPipelineTaskWithIDs(failTaskCreation: true);
        $response->assertStatus(404);
        $response->assertExactJson([
            'success'       => false,
            'code'          => 404,
            'data'          => [],
            'message'       => 'Unable to find requested task',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToUpdatePipelineTaskWithInvalidPipeline(): void
    {
        $this->createAndGetPipelineTaskWithIDs();
        $response = $this->patch('/task-manager/pipelines/' . Str::uuid()->toString() . '/tasks/' . $this->getTaskIdentifier(), [
            'order'             =>  1,
        ]);
        $response->assertStatus(404);
        $response->assertExactJson([
            'success'       => false,
            'code'          => 404,
            'data'          => [],
            'message'       => 'Unable to find requested pipeline',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToUpdatePipelineTaskWithInvalidTask(): void
    {
        $this->createAndGetPipelineTaskWithIDs();

        $response = $this->patch('/task-manager/pipelines/' . $this->getPipelineIdentifier() . '/tasks/' . Str::uuid()->toString(), [
            'order'             =>  2,
        ]);
        $response->assertStatus(404);
        $response->assertExactJson([
            'success'       => false,
            'code'          => 404,
            'data'          => [],
            'message'       => 'Unable to find requested task',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfPipelineTaskCanBeUpdatedWithUuids(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineUuid = $this->getPipelineIdentifier();
        $taskUuid = $this->getTaskIdentifier();

        $response = $this->patch('/task-manager/pipelines/' . $pipelineUuid . '/tasks/' . $taskUuid, [
            'order'     =>  2,
        ]);
        $response->assertStatus(200);
        $response->assertExactJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  [],
            'message'       =>  'Successfully updated pipeline task',
        ]);

        $this->assertPipelineTaskHasSamePipeline($pipelineUuid);
        $this->assertPipelineTaskHasSameTask($taskUuid);
        $this->assertPipelineTaskHasSameOrder(2);
    }

    /**
     * @return void
     */
    public function testShouldPassIfPipelineTaskCanBeUpdatedWithIds(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineId = $this->getPipelineIdentifier(true);
        $taskId = $this->getTaskIdentifier(true);

        $response = $this->patch('/task-manager/pipelines/' . $pipelineId . '/tasks/' . $taskId, [
            'order'     =>  3,
        ]);
        $response->assertStatus(200);
        $response->assertExactJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  [],
            'message'       =>  'Successfully updated pipeline task',
        ]);

        $this->assertPipelineTaskHasSamePipeline($this->getPipelineIdentifier());
        $this->assertPipelineTaskHasSameTask($this->getTaskIdentifier());
        $this->assertPipelineTaskHasSameOrder(3);
    }

    /**
     * @return void
     */
    public function testShouldPassIfPipelineTaskCanBeDeletedWithUuids(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineUuid = $this->getPipelineIdentifier();
        $taskUuid = $this->getTaskIdentifier();

        $response = $this->delete('/task-manager/pipelines/' . $pipelineUuid . '/tasks/' . $taskUuid);
        $response->assertStatus(200);
        $response->assertExactJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  [],
            'message'       =>  'Successfully deleted pipeline task',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfPipelineTaskCanBeDeletedWithIds(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineId = $this->getPipelineIdentifier(true);
        $taskId = $this->getTaskIdentifier(true);

        $response = $this->delete('/task-manager/pipelines/' . $pipelineId . '/tasks/' . $taskId);
        $response->assertStatus(200);
        $response->assertExactJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  [],
            'message'       =>  'Successfully deleted pipeline task',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToDeletePipelineTaskWithInvalidPipelineIdentifierAsUuid(): void
    {
        $this->createAndGetPipelineTask();
        $response = $this->delete('/task-manager/pipelines/' . Str::uuid()->toString() . '/tasks/' . $this->getTaskIdentifier());
        $response->assertStatus(404);
        $response->assertExactJson([
            'success'       =>  false,
            'code'          =>  404,
            'data'          =>  [],
            'message'       =>  'Unable to find requested pipeline',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToDeletePipelineTaskWithInvalidPipelineIdentifierAsId(): void
    {
        $this->createAndGetPipelineTask();
        $response = $this->delete('/task-manager/pipelines/2/tasks/' . $this->getTaskIdentifier(true));
        $response->assertStatus(404);
        $response->assertExactJson([
            'success'       =>  false,
            'code'          =>  404,
            'data'          =>  [],
            'message'       =>  'Unable to find requested pipeline',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToDeletePipelineTaskWithInvalidTaskIdentifierAsUuid(): void
    {
        $this->createAndGetPipelineTask();
        $response = $this->delete('/task-manager/pipelines/' . $this->getPipelineIdentifier() . '/tasks/' . Str::uuid()->toString());
        $response->assertStatus(404);
        $response->assertExactJson([
            'success'       =>  false,
            'code'          =>  404,
            'data'          =>  [],
            'message'       =>  'Unable to find requested task',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToDeletePipelineTaskWithInvalidTaskIdentifierAsId(): void
    {
        $this->createAndGetPipelineTask();
        $response = $this->delete('/task-manager/pipelines/' . $this->getPipelineIdentifier(true) . '/tasks/2');
        $response->assertStatus(404);
        $response->assertExactJson([
            'success'       =>  false,
            'code'          =>  404,
            'data'          =>  [],
            'message'       =>  'Unable to find requested task',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfAbleToRetrievePipelineTaskWithUuids(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineIdentifier = $this->getPipelineIdentifier();
        $taskIdentifier = $this->getTaskIdentifier();

        $response = $this->get('/task-manager/pipelines/' . $pipelineIdentifier . '/tasks/' . $taskIdentifier);
        $response->assertStatus(200);

        $data = array_merge(
            [
                'pipeline'      =>  $this->getPipeline()->toArray(),
                'task'          =>  $this->getTask()->toArray(),
            ],
            $this->getPipelineTask()->toArray(),
        );

        $response->assertExactJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  $data,
            'message'       =>  'Successfully fetched pipeline task information',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfAbleToRetrievePipelineTaskWithIds(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineIdentifier = $this->getPipelineIdentifier(true);
        $taskIdentifier = $this->getTaskIdentifier(true);

        $response = $this->get('/task-manager/pipelines/' . $pipelineIdentifier . '/tasks/' . $taskIdentifier);
        $response->assertStatus(200);

        $data = array_merge(
            [
                'pipeline'      =>  $this->getPipeline()->toArray(),
                'task'          =>  $this->getTask()->toArray(),
            ],
            $this->getPipelineTask()->toArray(),
        );

        $response->assertExactJson([
            'success'           =>  true,
            'code'              =>  200,
            'data'              =>  $data,
            'message'           =>  'Successfully fetched pipeline task information',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRetrievePipelineTaskWithInvalidPipelineIdentifierAndUuids(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineIdentifier = Str::uuid()->toString();
        $pipelineIdentifier = $this->getTaskIdentifier();

        $response = $this->get('/task-manager/pipelines/' . $pipelineIdentifier . '/tasks/' . $pipelineIdentifier);
        $response->assertStatus(404);
        $response->assertExactJson([
            'success'       =>  false,
            'code'          =>  404,
            'data'          =>  [],
            'message'       =>  'Unable to find requested pipeline',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRetrievePipelineTaskWithInvalidPipelineIdentifierAndIds(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineIdentifier = 2;
        $taskIdentifier = $this->getTaskIdentifier(true);

        $response = $this->get('/task-manager/pipelines/' . $pipelineIdentifier . '/tasks/' . $taskIdentifier);
        $response->assertStatus(404);
        $response->assertExactJson([
            'success'       =>  false,
            'code'          =>  404,
            'data'          =>  [],
            'message'       =>  'Unable to find requested pipeline',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRetrievePipelineTaskWithInvalidTaskIdentifierAndUuids(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineIdentifier = $this->getPipelineIdentifier();
        $taskIdentifier = Str::uuid()->toString();

        $response = $this->get('/task-manager/pipelines/' . $pipelineIdentifier . '/tasks/' . $taskIdentifier);
        $response->assertStatus(404);
        $response->assertExactJson([
            'success'       =>  false,
            'code'          =>  404,
            'data'          =>  [],
            'message'       =>  'Unable to find requested task',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRetrievePipelineTaskWithInvalidTaskIdentifierAndIds(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineIdentifier = $this->getPipelineIdentifier(true);
        $taskIdentifier = 2;

        $response = $this->get('/task-manager/pipelines/' . $pipelineIdentifier . '/tasks/' . $taskIdentifier);
        $response->assertStatus(404);
        $response->assertExactJson([
            'success'       =>  false,
            'code'          =>  404,
            'data'          =>  [],
            'message'       =>  'Unable to find requested task',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfPipelineTaskCanBeRestoredUsingUuids(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineUuid = $this->getPipelineIdentifier();
        $taskUuid = $this->getTaskIdentifier();

        $this->restorePipelineTask($pipelineUuid, $taskUuid);
    }

    /**
     * @return void
     */
    public function testShouldPassIfPipelineTaskCanBeRestoredUsingIds(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineId = $this->getPipelineIdentifier(true);
        $taskId = $this->getTaskIdentifier(true);

        $this->restorePipelineTask($pipelineId, $taskId);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRestorePipelineTaskWithInvalidPipelineIdentifierUsingUuids(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineUuid = $this->getPipelineIdentifier();
        $taskUuid = $this->getTaskIdentifier();

        $this->restorePipelineTask($pipelineUuid, $taskUuid, failForPipelineIdentifier: true);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRestorePipelineTaskWithInvalidPipelineIdentifierUsingIds(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineId = $this->getPipelineIdentifier(true);
        $taskId = $this->getTaskIdentifier(true);

        $this->restorePipelineTask($pipelineId, $taskId, failForPipelineIdentifier: true);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRestorePipelineTaskWithInvalidTaskIdentifierUsingUuids(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineUuid = $this->getPipelineIdentifier();
        $taskUuid = $this->getTaskIdentifier();

        $this->restorePipelineTask($pipelineUuid, $taskUuid, failForTaskIdentifier: true);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRestorePipelineTaskWithInvalidTaskIdentifierUsingIds(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineId = $this->getPipelineIdentifier(true);
        $taskId = $this->getTaskIdentifier(true);

        $this->restorePipelineTask($pipelineId, $taskId, failForTaskIdentifier: true);
    }

    /**
     * Get created pipeline
     * @return PipelineInterface
     */
    private function getPipeline(): PipelineInterface
    {
        return Pipeline::all()->first();
    }

    /**
     * Get created pipeline identifier
     * @param bool $asInteger
     * @return string|int
     */
    private function getPipelineIdentifier(bool $asInteger = false): string|int
    {
        $model = $this->getPipeline();
        if ($asInteger) {
            return $model->getID();
        }
        return $model->getUuid();
    }

    /**
     * Get created task instance
     * @return TaskInterface
     */
    private function getTask(): TaskInterface
    {
        return Task::all()->first();
    }

    /**
     * Get created task identifier
     * @param bool $asInteger
     * @return string|int
     */
    private function getTaskIdentifier(bool $asInteger = false): string|int
    {
        $model = $this->getTask();
        if ($asInteger) {
            return $model->getID();
        }
        return $model->getUuid();
    }

    /**
     * Get created pipeline task
     * @return PipelineTaskInterface
     */
    private function getPipelineTask(): PipelineTaskInterface
    {
        return PipelineTask::all()->first();
    }

    /**
     * Get created pipeline task identifier
     * @return string
     */
    private function getPipelineTaskIdentifier(): string
    {
        return $this->getPipelineTask()->getUuid();
    }

    /**
     * Assert that created pipeline task has same pipeline as specified
     * @param string $pipeline
     * @return void
     */
    private function assertPipelineTaskHasSamePipeline(string $pipeline): void
    {
        $this->assertSame($pipeline, $this->getPipelineTask()->getPipelineUuid());
    }

    /**
     * Assert that created pipeline task has same task as specified
     * @param string $task
     * @return void
     */
    private function assertPipelineTaskHasSameTask(string $task): void
    {
        $this->assertSame($task, $this->getPipelineTask()->getTaskUuid());
    }

    /**
     * Assert that created pipeline task has same order as specified
     * @param int $order
     * @return void
     */
    private function assertPipelineTaskHasSameOrder(int $order): void
    {
        $this->assertSame($order, $this->getPipelineTask()->getOrder());
    }

    /**
     * Try to restore pipeline task with given parameters
     * @param string|int $pipelineIdentifier
     * @param string|int $taskIdentifier
     * @param bool $failForPipelineIdentifier
     * @param bool $failForTaskIdentifier
     * @return void
     */
    private function restorePipelineTask(
        string|int $pipelineIdentifier,
        string|int $taskIdentifier,
        bool $failForPipelineIdentifier = false,
        bool $failForTaskIdentifier = false
    ): void {
        $response = $this->delete('/task-manager/pipelines/' . $pipelineIdentifier . '/tasks/' . $taskIdentifier);
        $response->assertStatus(200);
        $response->assertExactJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  [],
            'message'       =>  'Successfully deleted pipeline task',
        ]);

        $response = $this->get('/task-manager/pipelines/' . $pipelineIdentifier . '/tasks');
        $response->assertExactJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  [],
            'message'       =>  'Successfully fetched list of tasks for requested pipeline',
        ]);

        if ($failForPipelineIdentifier) {
            $response = $this->patch('/task-manager/pipelines/' . Str::uuid()->toString() . '/tasks/' . $taskIdentifier . '/restore');
            $response->assertStatus(404);
            $response->assertExactJson([
                'success'       =>  false,
                'code'          =>  404,
                'data'          =>  [],
                'message'       =>  'Unable to find requested pipeline',
            ]);
        }

        if ($failForTaskIdentifier) {
            $response = $this->patch('/task-manager/pipelines/' . $pipelineIdentifier . '/tasks/' . Str::uuid()->toString() . '/restore');
            $response->assertStatus(404);
            $response->assertExactJson([
                'success'       =>  false,
                'code'          =>  404,
                'data'          =>  [],
                'message'       =>  'Unable to find requested task',
            ]);
        }

        if (!$failForPipelineIdentifier && !$failForTaskIdentifier) {
            $response = $this->patch('/task-manager/pipelines/' . $pipelineIdentifier . '/tasks/' . $taskIdentifier . '/restore');
            $response->assertStatus(200);
            $response->assertExactJson([
                'success'       =>  true,
                'code'          =>  200,
                'data'          =>  [],
                'message'       =>  'Successfully restored pipeline task reference',
            ]);

            $response = $this->get('/task-manager/pipelines/' . $pipelineIdentifier . '/tasks');
            $response->assertStatus(200);
            $data = $response->json('data');
            $this->assertCount(1, $data);
        }
    }
}
