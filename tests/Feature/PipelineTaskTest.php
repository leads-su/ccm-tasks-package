<?php

namespace ConsulConfigManager\Tasks\Test\Feature;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
        $response = $this->get('/task-manager/pipelines/' . $this->getPipelineStringIdentifier() . '/tasks');
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
        $response->assertStatus(201);
        $this->validatePipelineTask($response->json('data'));
        $this->assertDatabaseCount('pipeline_tasks', 1);
    }

    /**
     * @return void
     */
    public function testShouldPassIfPipelineTaskCanBeCreatedWithId(): void
    {
        $response = $this->createAndGetPipelineTaskWithIDs();
        $response->assertStatus(201);
        $this->validatePipelineTask($response->json('data'));
        $this->assertDatabaseCount('pipeline_tasks', 1);
    }

    /**
     * @return void
     */
    public function testShouldPassIfDuplicatePipelineTaskCannotBeCreated(): void
    {
        $response = $this->createAndGetPipelineTaskWithIDs();
        $response->assertStatus(201);
        $this->validatePipelineTask($response->json('data'));
        $this->assertDatabaseCount('pipeline_tasks', 1);

        $response =  $this->post('/task-manager/pipelines/' . $this->getPipelineStringIdentifier() . '/tasks', [
            'task_uuid'         =>  $this->getTaskStringIdentifier(),
            'order'             =>  1,
        ]);

        $response->assertStatus(409);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNonEmptyTasksListCanBeRetrieved(): void
    {
        $this->createAndGetPipelineTask();

        $response = $this->get('/task-manager/pipelines/' . $this->getPipelineStringIdentifier() . '/tasks');
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
        $response = $this->patch('/task-manager/pipelines/' . Str::uuid()->toString() . '/tasks/' . $this->getTaskStringIdentifier(), [
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

        $response = $this->patch('/task-manager/pipelines/' . $this->getPipelineStringIdentifier() . '/tasks/' . Str::uuid()->toString(), [
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
        $pipelineUuid = $this->getPipelineStringIdentifier();
        $taskUuid = $this->getTaskStringIdentifier();

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
        $pipelineId = $this->getPipelineIntegerIdentifier();
        $taskId = $this->getTaskIntegerIdentifier();

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

        $this->assertPipelineTaskHasSamePipeline($this->getPipelineStringIdentifier());
        $this->assertPipelineTaskHasSameTask($this->getTaskStringIdentifier());
        $this->assertPipelineTaskHasSameOrder(3);
    }

    /**
     * @return void
     */
    public function testShouldPassIfPipelineTaskCanBeDeletedWithUuids(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineUuid = $this->getPipelineStringIdentifier();
        $taskUuid = $this->getTaskStringIdentifier();

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
        $pipelineId = $this->getPipelineIntegerIdentifier();
        $taskId = $this->getTaskIntegerIdentifier();

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
        $response = $this->delete('/task-manager/pipelines/' . Str::uuid()->toString() . '/tasks/' . $this->getTaskStringIdentifier());
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
        $response = $this->delete('/task-manager/pipelines/2/tasks/' . $this->getTaskIntegerIdentifier());
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
        $response = $this->delete('/task-manager/pipelines/' . $this->getPipelineStringIdentifier() . '/tasks/' . Str::uuid()->toString());
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
        $response = $this->delete('/task-manager/pipelines/' . $this->getPipelineIntegerIdentifier() . '/tasks/2');
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
        $pipelineIdentifier = $this->getPipelineStringIdentifier();
        $taskIdentifier = $this->getTaskStringIdentifier();

        $response = $this->get('/task-manager/pipelines/' . $pipelineIdentifier . '/tasks/' . $taskIdentifier);
        $response->assertStatus(200);

        $data = $response->json('data');

        $this->assertArrayHasKey('history', $data);
        $this->assertArrayHasKey('task', $data);
        $this->assertArrayHasKey('pipeline', $data);
        $this->validateTask(Arr::get($data, 'task'));
        $this->validatePipeline(Arr::get($data, 'pipeline'));
        $response->assertJson([
            'success'           =>  true,
            'code'              =>  200,
            'data'              =>  $data,
            'message'           =>  'Successfully fetched pipeline task information',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfAbleToRetrievePipelineTaskWithIds(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineIdentifier = $this->getPipelineIntegerIdentifier();
        $taskIdentifier = $this->getTaskIntegerIdentifier();

        $response = $this->get('/task-manager/pipelines/' . $pipelineIdentifier . '/tasks/' . $taskIdentifier);
        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertArrayHasKey('history', $data);
        $this->assertArrayHasKey('task', $data);
        $this->assertArrayHasKey('pipeline', $data);
        $this->validateTask(Arr::get($data, 'task'));
        $this->validatePipeline(Arr::get($data, 'pipeline'));
        $response->assertJson([
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
        $taskIdentifier = $this->getTaskStringIdentifier();

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
    public function testShouldPassIfFailedToRetrievePipelineTaskWithInvalidPipelineIdentifierAndIds(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineIdentifier = 2;
        $taskIdentifier = $this->getTaskIntegerIdentifier();

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
        $pipelineIdentifier = $this->getPipelineStringIdentifier();
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
        $pipelineIdentifier = $this->getPipelineIntegerIdentifier();
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
        $pipelineUuid = $this->getPipelineStringIdentifier();
        $taskUuid = $this->getTaskStringIdentifier();

        $this->restorePipelineTask($pipelineUuid, $taskUuid);
    }

    /**
     * @return void
     */
    public function testShouldPassIfPipelineTaskCanBeRestoredUsingIds(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineId = $this->getPipelineIntegerIdentifier();
        $taskId = $this->getTaskIntegerIdentifier();

        $this->restorePipelineTask($pipelineId, $taskId);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRestorePipelineTaskWithInvalidPipelineIdentifierUsingUuids(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineUuid = $this->getPipelineStringIdentifier();
        $taskUuid = $this->getTaskStringIdentifier();

        $this->restorePipelineTask($pipelineUuid, $taskUuid, failForPipelineIdentifier: true);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRestorePipelineTaskWithInvalidPipelineIdentifierUsingIds(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineId = $this->getPipelineIntegerIdentifier();
        $taskId = $this->getTaskIntegerIdentifier();

        $this->restorePipelineTask($pipelineId, $taskId, failForPipelineIdentifier: true);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRestorePipelineTaskWithInvalidTaskIdentifierUsingUuids(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineUuid = $this->getPipelineStringIdentifier();
        $taskUuid = $this->getTaskStringIdentifier();

        $this->restorePipelineTask($pipelineUuid, $taskUuid, failForTaskIdentifier: true);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRestorePipelineTaskWithInvalidTaskIdentifierUsingIds(): void
    {
        $this->createAndGetPipelineTask();
        $pipelineId = $this->getPipelineIntegerIdentifier();
        $taskId = $this->getTaskIntegerIdentifier();

        $this->restorePipelineTask($pipelineId, $taskId, failForTaskIdentifier: true);
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
