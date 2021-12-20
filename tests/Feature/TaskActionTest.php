<?php

namespace ConsulConfigManager\Tasks\Test\Feature;

use Illuminate\Support\Str;
use ConsulConfigManager\Tasks\Models\Task;
use ConsulConfigManager\Tasks\Models\Action;
use ConsulConfigManager\Tasks\Models\TaskAction;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskActionInterface;

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
        $response = $this->get('/task-manager/tasks/' . $this->getTaskIdentifier() . '/actions');
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
    public function testShouldPassIfNotFoundReturnedOnNonExistentTask(): void
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
    public function testShouldPassIfTaskActionCanBeCreatedWithUuid(): void
    {
        $response = $this->createAndGetTaskAction();
        $response->assertJson([
            'success'           =>  true,
            'code'              =>  201,
            'data'              =>  [
                'action_uuid'   =>  $this->getActionIdentifier(),
                'deleted_at'    =>  null,
                'order'         =>  1,
                'task_uuid'     =>  $this->getTaskIdentifier(),
            ],
            'message'           =>  'Successfully created new task action',
        ]);
        $this->assertDatabaseCount('task_actions', 1);
    }

    /**
     * @return void
     */
    public function testShouldPassIfTaskActionCanBeCreatedWithId(): void
    {
        $response = $this->createAndGetTaskActionWithIDs();
        $response->assertJson([
            'success'           =>  true,
            'code'              =>  201,
            'data'              =>  [
                'action_uuid'   =>  $this->getActionIdentifier(),
                'deleted_at'    =>  null,
                'order'         =>  1,
                'task_uuid'     =>  $this->getTaskIdentifier(),
            ],
            'message'           =>  'Successfully created new task action',
        ]);
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
                'action_uuid'   =>  $this->getActionIdentifier(),
                'deleted_at'    =>  null,
                'order'         =>  1,
                'task_uuid'     =>  $this->getTaskIdentifier(),
            ],
            'message'           =>  'Successfully created new task action',
        ]);
        $this->assertDatabaseCount('task_actions', 1);

        $response =  $this->post('/task-manager/tasks/' . $this->getTaskIdentifier() . '/actions', [
            'action_uuid'       =>  $this->getActionIdentifier(),
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

        $response = $this->get('/task-manager/tasks/' . $this->getTaskIdentifier() . '/actions');
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
        $response = $this->patch('/task-manager/tasks/' . Str::uuid()->toString() . '/actions/' . $this->getActionIdentifier(), [
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

        $response = $this->patch('/task-manager/tasks/' . $this->getTaskIdentifier() . '/actions/' . Str::uuid()->toString(), [
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
        $taskUuid = $this->getTaskIdentifier();
        $actionUuid = $this->getActionIdentifier();

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
        $taskId = $this->getTaskIdentifier(true);
        $actionId = $this->getActionIdentifier(true);

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

        $this->assertTaskActionHasSameTask($this->getTaskIdentifier());
        $this->assertTaskActionHasSameAction($this->getActionIdentifier());
        $this->assertTaskActionHasSameOrder(3);
    }

    /**
     * @return void
     */
    public function testShouldPassIfTaskActionCanBeDeletedWithUuids(): void
    {
        $this->createAndGetTaskAction();
        $taskUuid = $this->getTaskIdentifier();
        $actionUuid = $this->getActionIdentifier();

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
        $taskId = $this->getTaskIdentifier(true);
        $actionId = $this->getActionIdentifier(true);

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
        $this->createAndGetTaskAction();
        $response = $this->delete('/task-manager/tasks/' . Str::uuid()->toString() . '/actions/' . $this->getActionIdentifier());
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
        $this->createAndGetTaskAction();
        $response = $this->delete('/task-manager/tasks/2/actions/' . $this->getActionIdentifier(true));
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
        $response = $this->delete('/task-manager/tasks/' . $this->getTaskIdentifier() . '/actions/' . Str::uuid()->toString());
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
        $response = $this->delete('/task-manager/tasks/' . $this->getTaskIdentifier(true) . '/actions/2');
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
        $taskIdentifier = $this->getTaskIdentifier();
        $actionIdentifier = $this->getActionIdentifier();

        $response = $this->get('/task-manager/tasks/' . $taskIdentifier . '/actions/' . $actionIdentifier);
        $response->assertStatus(200);

        $data = array_merge(
            [
                'action'        =>  $this->getAction()->toArray(),
                'task'          =>  $this->getTask()->toArray(),
            ],
            $this->getTaskAction()->toArray(),
        );

        $response->assertExactJson([
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
        $taskIdentifier = $this->getTaskIdentifier(true);
        $actionIdentifier = $this->getActionIdentifier(true);

        $response = $this->get('/task-manager/tasks/' . $taskIdentifier . '/actions/' . $actionIdentifier);
        $response->assertStatus(200);

        $data = array_merge(
            [
                'action'        =>  $this->getAction()->toArray(),
                'task'          =>  $this->getTask()->toArray(),
            ],
            $this->getTaskAction()->toArray(),
        );

        $response->assertExactJson([
            'success'           =>  true,
            'code'              =>  200,
            'data'              =>  $data,
            'message'           =>  'Successfully fetched task action information',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRetrieveTaskActionWithInvalidTaskIdentifierAndUuids(): void
    {
        $this->createAndGetTaskAction();
        $taskIdentifier = Str::uuid()->toString();
        $actionIdentifier = $this->getActionIdentifier();

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
        $actionIdentifier = $this->getActionIdentifier(true);

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
        $taskIdentifier = $this->getTaskIdentifier();
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
        $taskIdentifier = $this->getTaskIdentifier(true);
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
        $taskUuid = $this->getTaskIdentifier();
        $actionUuid = $this->getActionIdentifier();

        $this->restoreTaskAction($taskUuid, $actionUuid);
    }

    /**
     * @return void
     */
    public function testShouldPassIfTaskActionCanBeRestoredUsingIds(): void
    {
        $this->createAndGetTaskAction();
        $taskId = $this->getTaskIdentifier(true);
        $actionId = $this->getActionIdentifier(true);

        $this->restoreTaskAction($taskId, $actionId);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRestoreTaskActionWithInvalidTaskIdentifierUsingUuids(): void
    {
        $this->createAndGetTaskAction();
        $taskUuid = $this->getTaskIdentifier();
        $actionUuid = $this->getActionIdentifier();

        $this->restoreTaskAction($taskUuid, $actionUuid, failForTaskIdentifier: true);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRestoreTaskActionWithInvalidTaskIdentifierUsingIds(): void
    {
        $this->createAndGetTaskAction();
        $taskId = $this->getTaskIdentifier(true);
        $actionId = $this->getActionIdentifier(true);

        $this->restoreTaskAction($taskId, $actionId, failForTaskIdentifier: true);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRestoreTaskActionWithInvalidActionIdentifierUsingUuids(): void
    {
        $this->createAndGetTaskAction();
        $taskUuid = $this->getTaskIdentifier();
        $actionUuid = $this->getActionIdentifier();

        $this->restoreTaskAction($taskUuid, $actionUuid, failForActionIdentifier: true);
    }

    /**
     * @return void
     */
    public function testShouldPassIfFailedToRestoreTaskActionWithInvalidActionIdentifierUsingIds(): void
    {
        $this->createAndGetTaskAction();
        $taskId = $this->getTaskIdentifier(true);
        $actionId = $this->getActionIdentifier(true);

        $this->restoreTaskAction($taskId, $actionId, failForActionIdentifier: true);
    }

    /**
     * Get created task
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
     * Get created action instance
     * @return ActionInterface
     */
    private function getAction(): ActionInterface
    {
        return Action::all()->first();
    }

    /**
     * Get created action identifier
     * @param bool $asInteger
     * @return string|int
     */
    private function getActionIdentifier(bool $asInteger = false): string|int
    {
        $model = $this->getAction();
        if ($asInteger) {
            return $model->getID();
        }
        return $model->getUuid();
    }

    /**
     * Get created task action
     * @return TaskActionInterface
     */
    private function getTaskAction(): TaskActionInterface
    {
        return TaskAction::all()->first();
    }

    /**
     * Get created task action identifier
     * @return string
     */
    private function getTaskActionIdentifier(): string
    {
        return $this->getTaskAction()->getUuid();
    }

    /**
     * Assert that created task action has same task as specified
     * @param string $task
     * @return void
     */
    private function assertTaskActionHasSameTask(string $task): void
    {
        $this->assertSame($task, $this->getTaskAction()->getTaskUuid());
    }

    /**
     * Assert that created task action has same action as specified
     * @param string $action
     * @return void
     */
    private function assertTaskActionHasSameAction(string $action): void
    {
        $this->assertSame($action, $this->getTaskAction()->getActionUuid());
    }

    /**
     * Assert that created task action has same order as specified
     * @param int $order
     * @return void
     */
    private function assertTaskActionHasSameOrder(int $order): void
    {
        $this->assertSame($order, $this->getTaskAction()->getOrder());
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
