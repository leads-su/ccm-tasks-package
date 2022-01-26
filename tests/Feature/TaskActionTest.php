<?php

namespace ConsulConfigManager\Tasks\Test\Feature;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class TaskActionTest
 * @package ConsulConfigManager\Tasks\Test\Feature
 */
class TaskActionTest extends AbstractFeatureTest
{
    /**
     * @return void
     */
    public function testShouldPassIfEmptyActionsListCanBeRetrieved(): void
    {
        $this->createAndGetTaskAction();
        $response = $this->get('/task-manager/tasks/' . $this->getTaskStringIdentifier() . '/actions');
        $response->assertJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  [],
            'message'       =>  'Successfully fetched list of actions for requested task',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundReturnedOnNonExistentTaskWithId(): void
    {
        $response = $this->get('/task-manager/tasks/1/actions');
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
    public function testShouldPassIfNotFoundReturnedOnNonExistentTaskWithUuid(): void
    {
        $response = $this->get('/task-manager/tasks/67701d77-c123-48e9-a84b-41db955f2e09/actions');
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
    public function testShouldPassIfTaskActionCanBeCreatedWithUuid(): void
    {
        $response = $this->createAndGetTaskAction();
        $this->validateTaskAction($response->json('data'));
        $this->assertDatabaseCount('task_actions', 1);
    }

    /**
     * @return void
     */
    public function testShouldPassIfTaskActionCanBeCreatedWithId(): void
    {
        $response = $this->createAndGetTaskActionWithIDs();
        $this->validateTaskAction($response->json('data'));
        $this->assertDatabaseCount('task_actions', 1);
    }

    /**
     * @return void
     */
    public function testShouldPassIfDuplicateTaskActionCannotBeCreated(): void
    {
        $response = $this->createAndGetTaskActionWithIDs();
        $response->assertJson([
            'success'           =>  true,
            'code'              =>  201,
            'data'              =>  [
                'action_uuid'   =>  $this->getActionStringIdentifier(),
                'deleted_at'    =>  null,
                'order'         =>  1,
                'task_uuid'     =>  $this->getTaskStringIdentifier(),
            ],
            'message'           =>  'Successfully created new task action',
        ]);
        $this->assertDatabaseCount('task_actions', 1);

        $response =  $this->post('/task-manager/tasks/' . $this->getTaskStringIdentifier() . '/actions', [
            'action_uuid'       =>  $this->getActionStringIdentifier(),
            'order'             =>  1,
        ]);

        $response->assertStatus(409);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNonEmptyActionsListCanBeRetrieved(): void
    {
        $this->createAndGetTaskAction();

        $response = $this->get('/task-manager/tasks/' . $this->getTaskStringIdentifier() . '/actions');
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToCreateTaskActionWithInvalidTask(): void
    {
        $response = $this->createAndGetTaskActionWithIDs(failTaskCreation: true);
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
    public function testShouldPassIfFailedToCreateTaskActionWithInvalidAction(): void
    {
        $response = $this->createAndGetTaskActionWithIDs(failActionCreation: true);
        $response->assertStatus(404);
        $response->assertExactJson([
            'success'       => false,
            'code'          => 404,
            'data'          => [],
            'message'       => 'Unable to find requested action',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToUpdateTaskActionWithInvalidTask(): void
    {
        $this->createAndGetTaskActionWithIDs();
        $response = $this->patch('/task-manager/tasks/' . Str::uuid()->toString() . '/actions/' . $this->getActionStringIdentifier(), [
            'order'             =>  1,
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
    public function testShouldPassIfFailedToUpdateTaskActionWithInvalidAction(): void
    {
        $this->createAndGetTaskActionWithIDs();

        $response = $this->patch('/task-manager/tasks/' . $this->getTaskStringIdentifier() . '/actions/' . Str::uuid()->toString(), [
            'order'             =>  2,
        ]);
        $response->assertStatus(404);
        $response->assertExactJson([
            'success'       => false,
            'code'          => 404,
            'data'          => [],
            'message'       => 'Unable to find requested action',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfTaskActionCanBeUpdatedWithUuids(): void
    {
        $this->createAndGetTaskAction();
        $taskUuid = $this->getTaskStringIdentifier();
        $actionUuid = $this->getActionStringIdentifier();

        $response = $this->patch('/task-manager/tasks/' . $taskUuid . '/actions/' . $actionUuid, [
            'order'     =>  2,
        ]);
        $response->assertStatus(200);
        $response->assertExactJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  [],
            'message'       =>  'Successfully updated task action',
        ]);

        $this->assertTaskActionHasSameTask($taskUuid);
        $this->assertTaskActionHasSameAction($actionUuid);
        $this->assertTaskActionHasSameOrder(2);
    }

    /**
     * @return void
     */
    public function testShouldPassIfTaskActionCanBeUpdatedWithIds(): void
    {
        $this->createAndGetTaskAction();
        $taskId = $this->getTaskIntegerIdentifier();
        $actionId = $this->getActionIntegerIdentifier();

        $response = $this->patch('/task-manager/tasks/' . $taskId . '/actions/' . $actionId, [
            'order'     =>  3,
        ]);
        $response->assertStatus(200);
        $response->assertExactJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  [],
            'message'       =>  'Successfully updated task action',
        ]);

        $this->assertTaskActionHasSameTask($this->getTaskStringIdentifier());
        $this->assertTaskActionHasSameAction($this->getActionStringIdentifier());
        $this->assertTaskActionHasSameOrder(3);
    }

    /**
     * @return void
     */
    public function testShouldPassIfTaskActionCanBeDeletedWithUuids(): void
    {
        $this->createAndGetTaskAction();
        $taskUuid = $this->getTaskStringIdentifier();
        $actionUuid = $this->getActionStringIdentifier();

        $response = $this->delete('/task-manager/tasks/' . $taskUuid . '/actions/' . $actionUuid);
        $response->assertStatus(200);
        $response->assertExactJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  [],
            'message'       =>  'Successfully deleted task action',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfTaskActionCanBeDeletedWithIds(): void
    {
        $this->createAndGetTaskAction();
        $taskId = $this->getTaskIntegerIdentifier();
        $actionId = $this->getActionIntegerIdentifier();

        $response = $this->delete('/task-manager/tasks/' . $taskId . '/actions/' . $actionId);
        $response->assertStatus(200);
        $response->assertExactJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  [],
            'message'       =>  'Successfully deleted task action',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToDeleteTaskActionWithInvalidTaskIdentifierAsUuid(): void
    {
        $this->createAndGetTaskActionModel();
        $response = $this->delete('/task-manager/tasks/' . Str::uuid()->toString() . '/actions/' . $this->getActionStringIdentifier());
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
    public function testShouldPassIfFailedToDeleteTaskActionWithInvalidTaskIdentifierAsId(): void
    {
        $this->createAndGetTaskActionModel();
        $response = $this->delete('/task-manager/tasks/2/actions/' . $this->getActionIntegerIdentifier());
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
    public function testShouldPassIfFailedToDeleteTaskActionWithInvalidActionIdentifierAsUuid(): void
    {
        $this->createAndGetTaskAction();
        $response = $this->delete('/task-manager/tasks/' . $this->getTaskStringIdentifier() . '/actions/' . Str::uuid()->toString());
        $response->assertStatus(404);
        $response->assertExactJson([
            'success'       =>  false,
            'code'          =>  404,
            'data'          =>  [],
            'message'       =>  'Unable to find requested action',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToDeleteTaskActionWithInvalidActionIdentifierAsId(): void
    {
        $this->createAndGetTaskAction();
        $response = $this->delete('/task-manager/tasks/' . $this->getTaskIntegerIdentifier() . '/actions/2');
        $response->assertStatus(404);
        $response->assertExactJson([
            'success'       =>  false,
            'code'          =>  404,
            'data'          =>  [],
            'message'       =>  'Unable to find requested action',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfAbleToRetrieveTaskActionWithUuids(): void
    {
        $this->createAndGetTaskAction();
        $taskIdentifier = $this->getTaskStringIdentifier();
        $actionIdentifier = $this->getActionStringIdentifier();

        $response = $this->get('/task-manager/tasks/' . $taskIdentifier . '/actions/' . $actionIdentifier);
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertArrayHasKey('history', $data);
        $this->assertArrayHasKey('task', $data);
        $this->assertArrayHasKey('action', $data);
        $this->validateTask(Arr::get($data, 'task'));
        $this->validateAction(Arr::get($data, 'action'));

        $response->assertJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  $data,
            'message'       =>  'Successfully fetched task action information',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfAbleToRetrieveTaskActionWithIds(): void
    {
        $this->createAndGetTaskAction();
        $taskIdentifier = $this->getTaskIntegerIdentifier();
        $actionIdentifier = $this->getActionIntegerIdentifier();

        $response = $this->get('/task-manager/tasks/' . $taskIdentifier . '/actions/' . $actionIdentifier);
        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertArrayHasKey('history', $data);
        $this->assertArrayHasKey('task', $data);
        $this->assertArrayHasKey('action', $data);
        $this->validateTask(Arr::get($data, 'task'));
        $this->validateAction(Arr::get($data, 'action'));
        $response->assertJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  $data,
            'message'       =>  'Successfully fetched task action information',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRetrieveTaskActionWithInvalidTaskIdentifierAndUuids(): void
    {
        $this->createAndGetTaskAction();
        $taskIdentifier = Str::uuid()->toString();
        $actionIdentifier = $this->getActionStringIdentifier();

        $response = $this->get('/task-manager/tasks/' . $taskIdentifier . '/actions/' . $actionIdentifier);
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
    public function testShouldPassIfFailedToRetrieveTaskActionWithInvalidTaskIdentifierAndIds(): void
    {
        $this->createAndGetTaskAction();
        $taskIdentifier = 2;
        $actionIdentifier = $this->getActionIntegerIdentifier();

        $response = $this->get('/task-manager/tasks/' . $taskIdentifier . '/actions/' . $actionIdentifier);
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
    public function testShouldPassIfFailedToRetrieveTaskActionWithInvalidActionIdentifierAndUuids(): void
    {
        $this->createAndGetTaskAction();
        $taskIdentifier = $this->getTaskStringIdentifier();
        $actionIdentifier = Str::uuid()->toString();

        $response = $this->get('/task-manager/tasks/' . $taskIdentifier . '/actions/' . $actionIdentifier);
        $response->assertStatus(404);
        $response->assertExactJson([
            'success'       =>  false,
            'code'          =>  404,
            'data'          =>  [],
            'message'       =>  'Unable to find requested action',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRetrieveTaskActionWithInvalidActionIdentifierAndIds(): void
    {
        $this->createAndGetTaskAction();
        $taskIdentifier = $this->getTaskIntegerIdentifier();
        $actionIdentifier = 2;

        $response = $this->get('/task-manager/tasks/' . $taskIdentifier . '/actions/' . $actionIdentifier);
        $response->assertStatus(404);
        $response->assertExactJson([
            'success'       =>  false,
            'code'          =>  404,
            'data'          =>  [],
            'message'       =>  'Unable to find requested action',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfTaskActionCanBeRestoredUsingUuids(): void
    {
        $this->createAndGetTaskAction();
        $taskUuid = $this->getTaskStringIdentifier();
        $actionUuid = $this->getActionStringIdentifier();

        $this->restoreTaskAction($taskUuid, $actionUuid);
    }

    /**
     * @return void
     */
    public function testShouldPassIfTaskActionCanBeRestoredUsingIds(): void
    {
        $this->createAndGetTaskAction();
        $taskId = $this->getTaskIntegerIdentifier();
        $actionId = $this->getActionIntegerIdentifier();

        $this->restoreTaskAction($taskId, $actionId);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRestoreTaskActionWithInvalidTaskIdentifierUsingUuids(): void
    {
        $this->createAndGetTaskAction();
        $taskUuid = $this->getTaskStringIdentifier();
        $actionUuid = $this->getActionStringIdentifier();

        $this->restoreTaskAction($taskUuid, $actionUuid, failForTaskIdentifier: true);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRestoreTaskActionWithInvalidTaskIdentifierUsingIds(): void
    {
        $this->createAndGetTaskAction();
        $taskId = $this->getTaskIntegerIdentifier();
        $actionId = $this->getActionIntegerIdentifier();

        $this->restoreTaskAction($taskId, $actionId, failForTaskIdentifier: true);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRestoreTaskActionWithInvalidActionIdentifierUsingUuids(): void
    {
        $this->createAndGetTaskAction();
        $taskUuid = $this->getTaskStringIdentifier();
        $actionUuid = $this->getActionStringIdentifier();

        $this->restoreTaskAction($taskUuid, $actionUuid, failForActionIdentifier: true);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRestoreTaskActionWithInvalidActionIdentifierUsingIds(): void
    {
        $this->createAndGetTaskAction();
        $taskId = $this->getTaskIntegerIdentifier();
        $actionId = $this->getActionIntegerIdentifier();

        $this->restoreTaskAction($taskId, $actionId, failForActionIdentifier: true);
    }

    /**
     * Try to restore task action with given parameters
     * @param string|int $taskIdentifier
     * @param string|int $actionIdentifier
     * @param bool $failForTaskIdentifier
     * @param bool $failForActionIdentifier
     * @return void
     */
    private function restoreTaskAction(
        string|int $taskIdentifier,
        string|int $actionIdentifier,
        bool $failForTaskIdentifier = false,
        bool $failForActionIdentifier = false
    ): void {
        $response = $this->delete('/task-manager/tasks/' . $taskIdentifier . '/actions/' . $actionIdentifier);
        $response->assertStatus(200);
        $response->assertExactJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  [],
            'message'       =>  'Successfully deleted task action',
        ]);

        $response = $this->get('/task-manager/tasks/' . $taskIdentifier . '/actions');
        $response->assertExactJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  [],
            'message'       =>  'Successfully fetched list of actions for requested task',
        ]);

        if ($failForTaskIdentifier) {
            $response = $this->patch('/task-manager/tasks/' . Str::uuid()->toString() . '/actions/' . $actionIdentifier . '/restore');
            $response->assertStatus(404);
            $response->assertExactJson([
                'success'       =>  false,
                'code'          =>  404,
                'data'          =>  [],
                'message'       =>  'Unable to find requested task',
            ]);
        }

        if ($failForActionIdentifier) {
            $response = $this->patch('/task-manager/tasks/' . $taskIdentifier . '/actions/' . Str::uuid()->toString() . '/restore');
            $response->assertStatus(404);
            $response->assertExactJson([
                'success'       =>  false,
                'code'          =>  404,
                'data'          =>  [],
                'message'       =>  'Unable to find requested action',
            ]);
        }

        if (!$failForTaskIdentifier && !$failForActionIdentifier) {
            $response = $this->patch('/task-manager/tasks/' . $taskIdentifier . '/actions/' . $actionIdentifier . '/restore');
            $response->assertStatus(200);
            $response->assertExactJson([
                'success'       =>  true,
                'code'          =>  200,
                'data'          =>  [],
                'message'       =>  'Successfully restored task action reference',
            ]);

            $response = $this->get('/task-manager/tasks/' . $taskIdentifier . '/actions');
            $response->assertStatus(200);
            $data = $response->json('data');
            $this->assertCount(1, $data);
        }
    }
}
