<?php

namespace ConsulConfigManager\Tasks\Test\Feature;

use Illuminate\Support\Arr;

/**
 * Class TaskTest
 * @package ConsulConfigManager\Tasks\Test\Feature
 */
class TaskTest extends AbstractFeatureTest
{
    /**
     * @return void
     */
    public function testShouldPassIfEmptyTasksListCanBeRetrieved(): void
    {
        $response = $this->get('/task-manager/tasks');
        $response->assertStatus(200);
        $response->assertExactJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  [],
            'message'       =>  'Successfully fetched list of tasks',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNonEmptyTasksListCanBeRetrieved(): void
    {
        $this->createAndGetTask();
        $response = $this->get('/task-manager/tasks');
        $response->assertStatus(200);
        $tasks = $response->json('data');
        $this->validateTasksArray(
            tasks: $tasks,
            only: [
                'id', 'uuid', 'name', 'description',
                'type', 'created_at', 'updated_at',
                'deleted_at', 'servers',
            ]
        );
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromCreate(): void
    {
        $response = $this->createAndGetTask();
        $response->assertStatus(201);
        $data = $response->json('data');
        $this->validateTask($data);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromGetWithId(): void
    {
        $response = $this->get('/task-manager/tasks/100');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromGetWithUuid(): void
    {
        $response = $this->get('/task-manager/tasks/ced57182-a253-44f4-9d76-b6e04e5b2890');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromUpdateWithId(): void
    {
        $response = $this->patch('/task-manager/tasks/100', [
            'name'              =>  'Example Task',
            'description'       =>  'This is description for Example Task',
        ]);
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromUpdateWithUuid(): void
    {
        $response = $this->patch('/task-manager/tasks/ced57182-a253-44f4-9d76-b6e04e5b2890', [
            'name'              =>  'Example Task',
            'description'       =>  'This is description for Example Task',
        ]);
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromUpdateWithId(): void
    {
        $createResponse = $this->createAndGetTask();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->patch('/task-manager/tasks/' . Arr::get($createData, 'id'), [
            'name'              =>  'New Example Task',
            'description'       =>  'This is description for Example Task',
            'fail_on_error'     =>  Arr::get($createData, 'fail_on_error'),
            'actions'           =>  array_merge(
                $this->getTaskActions($createData)->toArray(),
                [$this->createAndGetActionModel()->toArray()]
            ),
        ]);
        $this->validateTask(
            task: $response->json('data'),
            expectedNew: [
                'name'              =>  'New Example Task',
            ]
        );
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromUpdateWithUuid(): void
    {
        $createResponse = $this->createAndGetTask();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->patch('/task-manager/tasks/' . Arr::get($createData, 'uuid'), [
            'name'              =>  'New Example Task',
            'description'       =>  'This is description for Example Task',
            'fail_on_error'     =>  Arr::get($createData, 'fail_on_error'),
            'actions'           =>  array_merge(
                $this->getTaskActions($createData)->toArray(),
                [$this->createAndGetActionModel()->toArray()]
            ),
        ]);
        $this->validateTask(
            task: $response->json('data'),
            expectedNew: [
                'name'              =>  'New Example Task',
            ]
        );
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromGetWithId(): void
    {
        $createResponse = $this->createAndGetTask();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->get('/task-manager/tasks/' . Arr::get($createData, 'id'));
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->validateTask($data);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromGetWithUuid(): void
    {
        $createResponse = $this->createAndGetTask();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->get('/task-manager/tasks/' . Arr::get($createData, 'uuid'));
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->validateTask($data);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromDeleteWithID(): void
    {
        $response = $this->delete('/task-manager/tasks/100');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromDeleteWithUuid(): void
    {
        $response = $this->delete('/task-manager/tasks/ced57182-a253-44f4-9d76-b6e04e5b2890');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromDeleteWithID(): void
    {
        $createResponse = $this->createAndGetTask();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->delete('/task-manager/tasks/' . Arr::get($createData, 'id'));
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromDeleteWithUuid(): void
    {
        $createResponse = $this->createAndGetTask();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->delete('/task-manager/tasks/' . Arr::get($createData, 'uuid'));
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromRestoreWithID(): void
    {
        $response = $this->patch('/task-manager/tasks/100/restore');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromRestoreWithUuid(): void
    {
        $response = $this->patch('/task-manager/tasks/ced57182-a253-44f4-9d76-b6e04e5b2890/restore');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromRestoreWithID(): void
    {
        $createResponse = $this->createAndGetTask();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->delete('/task-manager/tasks/' . Arr::get($createData, 'id'));
        $response->assertStatus(200);
        $response = $this->patch('/task-manager/tasks/' . Arr::get($createData, 'uuid') . '/restore');
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromRestoreWithUuid(): void
    {
        $createResponse = $this->createAndGetTask();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->delete('/task-manager/tasks/' . Arr::get($createData, 'uuid'));
        $response->assertStatus(200);
        $response = $this->patch('/task-manager/tasks/' . Arr::get($createData, 'uuid') . '/restore');
        $response->assertStatus(200);
    }
}
