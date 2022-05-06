<?php

namespace ConsulConfigManager\Tasks\Test\Feature;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Enums\ActionType;

/**
 * Class ActionTest
 * @package ConsulConfigManager\Tasks\Test\Feature
 */
class ActionTest extends AbstractFeatureTest
{
    /**
     * @return void
     */
    public function testShouldPassIfEmptyActionsListCanBeRetrieved(): void
    {
        $response = $this->get('/task-manager/actions');
        $response->assertStatus(200);
        $response->assertExactJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  [],
            'message'       =>  'Successfully fetched list of actions',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNonEmptyActionsListCanBeRetrieved(): void
    {
        $this->createAndGetAction();
        $response = $this->get('/task-manager/actions');
        $response->assertStatus(200);
        $actions = $response->json('data');
        $this->validateActionsArray(
            actions: $actions,
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
        $response = $this->createAndGetAction();
        $response->assertStatus(201);
        $data = $response->json('data');
        $this->validateAction($data);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromGetWithId(): void
    {
        $response = $this->get('/task-manager/actions/100');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromGetWithUuid(): void
    {
        $response = $this->get('/task-manager/actions/11111111-1111-1111-1111-111111111111');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromUpdateWithId(): void
    {
        $response = $this->patch('/task-manager/actions/100', [
            'name'              =>  'Example Action',
            'description'       =>  'This is description for Example Action',
            'type'              =>  ActionType::LOCAL,
            'command'           =>  'lsb_release',
            'arguments'         =>  ['-a'],
            'working_dir'       =>  null,
            'run_as'            =>  null,
            'use_sudo'          =>  false,
            'fail_on_error'     =>  true,
        ]);
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromUpdateWithUuid(): void
    {
        $response = $this->patch('/task-manager/actions/11111111-1111-1111-1111-111111111111', [
            'name'              =>  'Example Action',
            'description'       =>  'This is description for Example Action',
            'type'              =>  ActionType::LOCAL,
            'command'           =>  'lsb_release',
            'arguments'         =>  ['-a'],
            'working_dir'       =>  null,
            'run_as'            =>  null,
            'use_sudo'          =>  false,
            'fail_on_error'     =>  true,
        ]);
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromUpdateWithID(): void
    {
        $createResponse = $this->createAndGetAction();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->patch('/task-manager/actions/' . Arr::get($createData, 'id'), [
            'name'              =>  'New Example Action',
            'description'       =>  'This is description for Example Action',
            'type'              =>  ActionType::LOCAL,
            'command'           =>  'lsb_release',
            'arguments'         =>  ['-a'],
            'working_dir'       =>  null,
            'run_as'            =>  null,
            'use_sudo'          =>  false,
            'fail_on_error'     =>  true,
        ]);
        $this->validateAction(
            action: $response->json('data'),
            expectedNew: [
                'name'              =>  'New Example Action',
                'type'              =>  ActionType::LOCAL,
            ]
        );
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromUpdateWithUuid(): void
    {
        $createResponse = $this->createAndGetAction();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->patch('/task-manager/actions/' . Arr::get($createData, 'uuid'), [
            'name'              =>  'New Example Action',
            'description'       =>  'This is description for Example Action',
            'type'              =>  ActionType::REMOTE,
            'command'           =>  'lsb_release',
            'arguments'         =>  ['-a'],
            'working_dir'       =>  null,
            'run_as'            =>  null,
            'use_sudo'          =>  false,
            'fail_on_error'     =>  true,
        ]);
        $this->validateAction(
            action: $response->json('data'),
            expectedNew: [
                'name'              =>  'New Example Action',
                'type'              =>  ActionType::REMOTE,
            ]
        );
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromGetWithId(): void
    {
        $createResponse = $this->createAndGetAction();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->get('/task-manager/actions/' . Arr::get($createData, 'id'));
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->validateAction($data);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromGetWithUuid(): void
    {
        $createResponse = $this->createAndGetAction();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->get('/task-manager/actions/' . Arr::get($createData, 'uuid'));
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->validateAction($data);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromDeleteWithId(): void
    {
        $response = $this->delete('/task-manager/actions/100');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromDeleteWithId(): void
    {
        $createResponse = $this->createAndGetAction();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->delete('/task-manager/actions/' . Arr::get($createData, 'id'));
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromDeleteWithUuid(): void
    {
        $createResponse = $this->createAndGetAction();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->delete('/task-manager/actions/' . Arr::get($createData, 'uuid'));
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromRestoreWithId(): void
    {
        $createResponse = $this->createAndGetAction();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->delete('/task-manager/actions/' . Arr::get($createData, 'id'));
        $response->assertStatus(200);

        $response = $this->patch('/task-manager/actions/' . Arr::get($createData, 'id') . '/restore');
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromRestoreWithId(): void
    {
        $response = $this->patch('/task-manager/actions/100/restore');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromRestoreWithUuid(): void
    {
        $response = $this->patch('/task-manager/actions/11111111-1111-1111-1111-111111111111/restore');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromRestoreWithUuid(): void
    {
        $createResponse = $this->createAndGetAction();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->delete('/task-manager/actions/' . Arr::get($createData, 'uuid'));
        $response->assertStatus(200);

        $response = $this->patch('/task-manager/actions/' . Arr::get($createData, 'uuid') . '/restore');
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromDeleteWithUuid(): void
    {
        $response = $this->delete('/task-manager/actions/11111111-1111-1111-1111-111111111111');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromListExecutionsWithUuid(): void
    {
        $response = $this->get('/task-manager/actions/11111111-1111-1111-1111-111111111111/executions');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromListExecutionsWithId(): void
    {
        $response = $this->get('/task-manager/actions/100/executions');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfDataResponseReturnedFromListExecutionsWithUuid(): void
    {
        $actionResponse = $this->createAndGetAction();
        $actionResponse->assertStatus(201);
        $actionData = $actionResponse->json('data');
        $executionData = $this->createAndGetActionExecution();

        $response = $this->get('/task-manager/actions/' . Arr::get($actionData, 'uuid') . '/executions');
        $response->assertStatus(200);
        $response->assertJson([
            'success'           =>  true,
            'code'              =>  200,
            'data'              =>  [
                0               =>  [
                    'id'            =>  $executionData->getID(),
                    'state'         =>  $executionData->getState(),
                    'server_uuid'   =>  $executionData->getServerUuid(),
                    'server'        =>  null,
                ],
            ],
            'message'           =>  'Successfully fetched list of action executions',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfDataResponseReturnedFromListExecutionsWithId(): void
    {
        $actionResponse = $this->createAndGetAction();
        $actionResponse->assertStatus(201);
        $actionData = $actionResponse->json('data');
        $executionData = $this->createAndGetActionExecution();

        $response = $this->get('/task-manager/actions/' . Arr::get($actionData, 'id') . '/executions');
        $response->assertStatus(200);
        $response->assertJson([
            'success'           =>  true,
            'code'              =>  200,
            'data'              =>  [
                0               =>  [
                    'id'            =>  $executionData->getID(),
                    'state'         =>  $executionData->getState(),
                    'server_uuid'   =>  $executionData->getServerUuid(),
                    'server'        =>  null,
                ],
            ],
            'message'           =>  'Successfully fetched list of action executions',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromGetExecutionWithUuid(): void
    {
        $actionResponse = $this->createAndGetAction();
        $actionResponse->assertStatus(201);
        $actionData = $actionResponse->json('data');

        $response = $this->get('/task-manager/actions/' . Arr::get($actionData, 'uuid') . '/executions/1');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromGetExecutionWithId(): void
    {
        $actionResponse = $this->createAndGetAction();
        $actionResponse->assertStatus(201);
        $actionData = $actionResponse->json('data');

        $response = $this->get('/task-manager/actions/' . Arr::get($actionData, 'id') . '/executions/1');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfDataResponseReturnedFromGetExecutionWithUuid(): void
    {
        $actionResponse = $this->createAndGetAction();
        $actionResponse->assertStatus(201);
        $actionData = $actionResponse->json('data');
        $executionData = $this->createAndGetActionExecution();

        $response = $this->get('/task-manager/actions/' . Arr::get($actionData, 'uuid') . '/executions/' . $executionData->getID());
        $response->assertStatus(200);
        $response->assertJson([
            'success'           =>  true,
            'code'              =>  200,
            'data'              =>  [
                'id'            =>  $executionData->getID(),
                'state'         =>  $executionData->getState(),
                'server_uuid'   =>  $executionData->getServerUuid(),
                'server'        =>  null,
                'log'           =>  null,
            ],
            'message'           =>  'Successfully fetched action execution information',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfDataResponseReturnedFromGetExecutionWithId(): void
    {
        $actionResponse = $this->createAndGetAction();
        $actionResponse->assertStatus(201);
        $actionData = $actionResponse->json('data');
        $executionData = $this->createAndGetActionExecution();

        $response = $this->get('/task-manager/actions/' . Arr::get($actionData, 'id') . '/executions/' . $executionData->getID());
        $response->assertStatus(200);
        $response->assertJson([
            'success'           =>  true,
            'code'              =>  200,
            'data'              =>  [
                'id'            =>  $executionData->getID(),
                'state'         =>  $executionData->getState(),
                'server_uuid'   =>  $executionData->getServerUuid(),
                'server'        =>  null,
                'log'           =>  null,
            ],
            'message'           =>  'Successfully fetched action execution information',
        ]);
    }
}
