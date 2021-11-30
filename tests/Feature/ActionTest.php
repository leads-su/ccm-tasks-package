<?php

namespace ConsulConfigManager\Tasks\Test\Feature;

use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\Enums\ActionType;

/**
 * Class ActionTest
 * @package ConsulConfigManager\Tasks\Test\Feature
 */
class ActionTest extends TestCase
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
        $decoded = $response->json();
        $this->assertArrayHasKey('uuid', Arr::get($decoded, 'data.0'));
        $this->assertArrayHasKey('created_at', Arr::get($decoded, 'data.0'));
        $this->assertArrayHasKey('updated_at', Arr::get($decoded, 'data.0'));
        Arr::forget($decoded, 'data.0.uuid');
        Arr::forget($decoded, 'data.0.created_at');
        Arr::forget($decoded, 'data.0.updated_at');
        ksort($decoded['data'][0]);

        $this->assertSame([
            'success'               =>  true,
            'code'                  =>  200,
            'data'                  =>  [
                [
                    'description'   =>  'This is description for example action',
                    'id'            =>  1,
                    'name'          =>  'Example Action',
                    'type'          =>  ActionType::LOCAL,
                ],
            ],
            'message'               =>  'Successfully fetched list of actions',
        ], $decoded);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromCreate(): void
    {
        $response = $this->createAndGetAction();
        $response->assertStatus(201);
        $data = $response->json('data');
        $this->assertSameAction($data);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromGetWithID(): void
    {
        $response = $this->get('/task-manager/actions/100');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromGetWithUuid(): void
    {
        $response = $this->get('/task-manager/actions/ced57182-a253-44f4-9d76-b6e04e5b2890');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromUpdateWithID(): void
    {
        $response = $this->patch('/task-manager/actions/100', [
            'name'              =>  'Example Action',
            'description'       =>  'This is description for example action',
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
        $response = $this->patch('/task-manager/actions/ced57182-a253-44f4-9d76-b6e04e5b2890', [
            'name'              =>  'Example Action',
            'description'       =>  'This is description for example action',
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
            'description'       =>  'This is description for example action',
            'type'              =>  ActionType::REMOTE,
            'command'           =>  'lsb_release',
            'arguments'         =>  ['-a'],
            'working_dir'       =>  null,
            'run_as'            =>  null,
            'use_sudo'          =>  false,
            'fail_on_error'     =>  true,
        ]);
        $this->assertSameAction($response->json('data'), [
            'name'              =>  'New Example Action',
            'type'              =>  ActionType::REMOTE,
        ]);
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
            'description'       =>  'This is description for example action',
            'type'              =>  ActionType::REMOTE,
            'command'           =>  'lsb_release',
            'arguments'         =>  ['-a'],
            'working_dir'       =>  null,
            'run_as'            =>  null,
            'use_sudo'          =>  false,
            'fail_on_error'     =>  true,
        ]);
        $this->assertSameAction($response->json('data'), [
            'name'              =>  'New Example Action',
            'type'              =>  ActionType::REMOTE,
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromGetWithID(): void
    {
        $createResponse = $this->createAndGetAction();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->get('/task-manager/actions/' . Arr::get($createData, 'id'));
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertSameAction($data);
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
        $this->assertSameAction($data);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromDeleteWithID(): void
    {
        $response = $this->delete('/task-manager/actions/100');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromDeleteWithUuid(): void
    {
        $response = $this->delete('/task-manager/actions/ced57182-a253-44f4-9d76-b6e04e5b2890');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromDeleteWithID(): void
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
     * Create new action model
     * @return TestResponse
     */
    private function createAndGetAction(): TestResponse
    {
        return $this->post('/task-manager/actions', [
            'name'              =>  'Example Action',
            'description'       =>  'This is description for example action',
            'type'              =>  ActionType::LOCAL,
            'command'           =>  'lsb_release',
            'arguments'         =>  ['-a'],
            'working_dir'       =>  null,
            'run_as'            =>  null,
            'use_sudo'          =>  false,
            'fail_on_error'     =>  true,
        ]);
    }

    /**
     * Assert that same action is returned
     * @param array $data
     * @param array $newExpected
     * @return void
     */
    private function assertSameAction(array $data, array $newExpected = []): void
    {
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('uuid', $data);
        $this->assertSame(Arr::get($newExpected, 'name', 'Example Action'), Arr::get($data, 'name'));
        $this->assertSame(Arr::get($newExpected, 'description', 'This is description for example action'), Arr::get($data, 'description'));
        $this->assertSame(Arr::get($newExpected, 'type', ActionType::LOCAL), Arr::get($data, 'type'));
        $this->assertSame(Arr::get($newExpected, 'command', 'lsb_release'), Arr::get($data, 'command'));
        $this->assertSame(Arr::get($newExpected, 'arguments', ['-a']), Arr::get($data, 'arguments'));
        $this->assertSame(Arr::get($newExpected, 'working_dir', null), Arr::get($data, 'working_dir'));
        $this->assertSame(Arr::get($newExpected, 'run_as', null), Arr::get($data, 'run_as'));
        $this->assertSame(Arr::get($newExpected, 'use_sudo', false), Arr::get($data, 'use_sudo'));
        $this->assertSame(Arr::get($data, 'fail_on_error', true), Arr::get($data, 'fail_on_error'));
    }
}
