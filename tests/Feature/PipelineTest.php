<?php

namespace ConsulConfigManager\Tasks\Test\Feature;

use Illuminate\Support\Arr;

/**
 * Class PipelineTest
 * @package ConsulConfigManager\Tasks\Test\Feature
 */
class PipelineTest extends AbstractFeatureTest
{
    /**
     * @return void
     */
    public function testShouldPassIfEmptyPipelinesListCanBeRetrieved(): void
    {
        $response = $this->get('/task-manager/pipelines');
        $response->assertStatus(200);
        $response->assertExactJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  [],
            'message'       =>  'Successfully fetched list of pipelines',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNonEmptyPipelinesListCanBeRetrieved(): void
    {
        $this->createAndGetPipeline();
        $response = $this->get('/task-manager/pipelines');
        $response->assertStatus(200);
        $pipelines = $response->json('data');
        $this->validatePipelinesArray(
            pipelines: $pipelines,
            only: [
                'id', 'uuid', 'name', 'description',
                'created_at', 'updated_at', 'deleted_at',
            ]
        );
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromCreate(): void
    {
        $response = $this->createAndGetPipeline();
        $response->assertStatus(201);
        $data = $response->json('data');
        $this->validatePipeline($data);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromGetWithID(): void
    {
        $response = $this->get('/task-manager/pipelines/100');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromGetWithUuid(): void
    {
        $response = $this->get('/task-manager/pipelines/ced57182-a253-44f4-9d76-b6e04e5b2890');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromUpdateWithID(): void
    {
        $response = $this->patch('/task-manager/pipelines/100', [
            'name'              =>  'Example Pipeline',
            'description'       =>  'This is description for Example Pipeline',
        ]);
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromUpdateWithUuid(): void
    {
        $response = $this->patch('/task-manager/pipelines/ced57182-a253-44f4-9d76-b6e04e5b2890', [
            'name'              =>  'Example Pipeline',
            'description'       =>  'This is description for Example Pipeline',
        ]);
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromUpdateWithID(): void
    {
        $createResponse = $this->createAndGetPipeline();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->patch('/task-manager/pipelines/' . Arr::get($createData, 'id'), [
            'name'              =>  'New Example Pipeline',
            'description'       =>  'This is description for Example Pipeline',
            'tasks'             =>  array_merge(
                $this->getPipelineTasks($createData)->toArray(),
                [$this->createAndGetTaskModel()->toArray()]
            ),
        ]);
        $this->validatePipeline(
            pipeline: $response->json('data'),
            expectedNew: [
                'name'              =>  'New Example Pipeline',
            ]
        );
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromUpdateWithUuid(): void
    {
        $createResponse = $this->createAndGetPipeline();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->patch('/task-manager/pipelines/' . Arr::get($createData, 'uuid'), [
            'name'              =>  'New Example Pipeline',
            'description'       =>  'This is description for Example Pipeline',
            'tasks'             =>  array_merge(
                $this->getPipelineTasks($createData)->toArray(),
                [$this->createAndGetTaskModel()->toArray()]
            ),
        ]);
        $this->validatePipeline(
            pipeline: $response->json('data'),
            expectedNew: [
                'name'              =>  'New Example Pipeline',
            ]
        );
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromGetWithID(): void
    {
        $createResponse = $this->createAndGetPipeline();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->get('/task-manager/pipelines/' . Arr::get($createData, 'id'));
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->validatePipeline($data);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromGetWithUuid(): void
    {
        $createResponse = $this->createAndGetPipeline();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->get('/task-manager/pipelines/' . Arr::get($createData, 'uuid'));
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->validatePipeline($data);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromDeleteWithID(): void
    {
        $response = $this->delete('/task-manager/pipelines/100');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromDeleteWithUuid(): void
    {
        $response = $this->delete('/task-manager/pipelines/ced57182-a253-44f4-9d76-b6e04e5b2890');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromDeleteWithID(): void
    {
        $createResponse = $this->createAndGetPipeline();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->delete('/task-manager/pipelines/' . Arr::get($createData, 'id'));
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromDeleteWithUuid(): void
    {
        $createResponse = $this->createAndGetPipeline();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->delete('/task-manager/pipelines/' . Arr::get($createData, 'uuid'));
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromRestoreWithID(): void
    {
        $response = $this->patch('/task-manager/pipelines/100/restore');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromRestoreWithUuid(): void
    {
        $response = $this->patch('/task-manager/pipelines/ced57182-a253-44f4-9d76-b6e04e5b2890/restore');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromRestoreWithID(): void
    {
        $createResponse = $this->createAndGetPipeline();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->delete('/task-manager/pipelines/' . Arr::get($createData, 'uuid'));
        $response->assertStatus(200);
        $response = $this->patch('/task-manager/pipelines/' . Arr::get($createData, 'id') . '/restore');
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromRestoreWithUuid(): void
    {
        $createResponse = $this->createAndGetPipeline();
        $createResponse->assertStatus(201);
        $createData = $createResponse->json('data');

        $response = $this->delete('/task-manager/pipelines/' . Arr::get($createData, 'uuid'));
        $response->assertStatus(200);
        $response = $this->patch('/task-manager/pipelines/' . Arr::get($createData, 'uuid') . '/restore');
        $response->assertStatus(200);
    }
}
