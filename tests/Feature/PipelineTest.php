<?php

namespace ConsulConfigManager\Tasks\Test\Feature;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Models\Task;
use ConsulConfigManager\Tasks\Models\Action;
use ConsulConfigManager\Tasks\Models\Pipeline;
use ConsulConfigManager\Tasks\Enums\ExecutionState;
use ConsulConfigManager\Consul\Agent\Models\Service;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use ConsulConfigManager\Tasks\Models\ActionExecutionLog;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionInterface;
use ConsulConfigManager\Consul\Agent\Interfaces\ServiceInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionInterface;

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
        $response = $this->get('/task-manager/pipelines/11111111-1111-1111-1111-111111111111');
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
        $response = $this->patch('/task-manager/pipelines/11111111-1111-1111-1111-111111111111', [
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
        $response = $this->delete('/task-manager/pipelines/11111111-1111-1111-1111-111111111111');
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
        $response = $this->patch('/task-manager/pipelines/11111111-1111-1111-1111-111111111111/restore');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromRunWithId(): void
    {
        $response = $this->get('/task-manager/pipelines/100/run');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundResponseReturnedFromRunWithUuid(): void
    {
        $response = $this->get('/task-manager/pipelines/11111111-1111-1111-1111-111111111111/run');
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

    /**
     * @return void
     */
    public function testShouldPassIfCorrectResponseReturnedFromRun(): void
    {
        $pipelineResponse = $this->createAndGetPipeline();
        $pipelineResponse->assertStatus(201);
        $pipeline = $pipelineResponse->json('data');

        $runResponse = $this->get('/task-manager/pipelines/' . Arr::get($pipeline, 'uuid') . '/run');
        $runResponse->assertExactJson([
            'success'       =>  true,
            'code'          =>  200,
            'data'          =>  [],
            'message'       =>  'Successfully started pipeline',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfEmptyListReturnedFromListExecutions(): void
    {
        $response = $this->get('/task-manager/pipelines/executions');
        $response->assertStatus(200);
        $response->assertExactJson([
            'success' => true,
            'code' => 200,
            'data' => [],
            'message' => 'Successfully fetched list of pipeline executions',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundReturnedFromListExecutionsWithUuid(): void
    {
        $response = $this->get('/task-manager/pipelines/11111111-1111-1111-1111-111111111111/execution');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfNotFoundReturnedFromListExecutionsWithId(): void
    {
        $response = $this->get('/task-manager/pipelines/100/execution');
        $response->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testShouldPassIfDataReturnedFromListExecutionsWithUuid(): void
    {
        $this->validatePipelineExecutionsResponse(false);
    }

    /**
     * @return void
     */
    public function testShouldPassIfDataReturnedFromListExecutionsWithId(): void
    {
        $this->validatePipelineExecutionsResponse(true);
    }

    /**
     * @return void
     */
    public function testShouldPassIfDataReturnedFromGetExecutionWithUuidAndCreatedAction(): void
    {
        $this->validatePipelineExecutionResponse(false, ExecutionState::CREATED);
    }

    /**
     * @return void
     */
    public function testShouldPassIfDataReturnedFromGetExecutionWithIdAndCreatedAction(): void
    {
        $this->validatePipelineExecutionResponse(true, ExecutionState::CREATED);
    }

    /**
     * @return void
     */
    public function testShouldPassIfDataReturnedFromGetExecutionWithUuidAndRunningAction(): void
    {
        $this->validatePipelineExecutionResponse(false, ExecutionState::EXECUTING);
    }

    /**
     * @return void
     */
    public function testShouldPassIfDataReturnedFromGetExecutionWithIdAndRunningAction(): void
    {
        $this->validatePipelineExecutionResponse(true, ExecutionState::EXECUTING);
    }

    /**
     * @return void
     */
    public function testShouldPassIfDataReturnedFromGetExecutionWithUuidAndFinishedAction(): void
    {
        $this->validatePipelineExecutionResponse(false, ExecutionState::SUCCESS);
    }

    /**
     * @return void
     */
    public function testShouldPassIfDataReturnedFromGetExecutionWithIdAndFinishedAction(): void
    {
        $this->validatePipelineExecutionResponse(true, ExecutionState::SUCCESS);
    }

    private function validatePipelineExecutionsResponse(bool $useIdentifier = false): void
    {
        [
            $task,
            $action,
            $server,
            $pipeline,
            $pipelineExecution,
            $pipelineExecutionIdentifier,
            $taskExecution
        ] = $this->prepareDatabaseForPipelineExecutionTests($useIdentifier);

        $validationJson = $this->buildJsonForExecutionsResponseValidation(
            $pipeline,
            $pipelineExecution,
            $task,
            $taskExecution,
            $server,
            $action
        );

        $response = $this->get('/task-manager/pipelines/executions');
        $response->assertStatus(200);

        $response->assertExactJson($validationJson);
    }

    private function buildJsonForExecutionsResponseValidation(
        PipelineInterface $pipelineModel,
        PipelineExecutionInterface $pipelineExecution,
        TaskInterface $taskModel,
        TaskExecutionInterface $taskExecution,
        ServiceInterface $server,
        ActionInterface $actionModel,
    ): array {
        $actionExecution = $this->createAndGetActionExecution();

        return [
            'success'                                                   =>  true,
            'code'                                                      =>  200,
            'data'                                                      =>  [
                [
                    'created_at'                            =>  $pipelineExecution->created_at->toDateTimeLocalString('microsecond') . 'Z',
                    'execution_uuid'                        =>  $pipelineExecution->getUuid(),
                    'id'                                    =>  $pipelineExecution->getID(),
                    'name'                                  =>  $pipelineModel->getName(),
                    'pipeline_uuid'                         =>  $pipelineModel->getUuid(),
                    'state'                                 =>  $pipelineExecution->getState(),
                    'tasks'                                 =>  [
                        [
                            'created_at'                    =>  $taskExecution->created_at->toDateTimeLocalString('microsecond') . 'Z',
                            'id'                            =>  $taskExecution->getID(),
                            'name'                          =>  $taskModel->getName(),
                            'servers'                       =>  [
                                $server->getUuid()          =>  [
                                    'actions'               =>  [
                                        [
                                            'created_at'    =>  $actionExecution->created_at->toDateTimeLocalString('microsecond') . 'Z',
                                            'id'            =>  $actionExecution->getID(),
                                            'name'          =>  $actionModel->getName(),
                                            'state'         =>  $actionExecution->getState(),
                                            'updated_at'    =>  $actionExecution->updated_at->toDateTimeLocalString('microsecond') . 'Z',
                                            'uuid'          =>  $actionModel->getUuid(),
                                        ],
                                    ],
                                    'address'               =>  $server->getAddress(),
                                    'datacenter'            =>  $server->getDatacenter(),
                                    'environment'           =>  $server->getEnvironment(),
                                    'identifier'            =>  $server->getIdentifier(),
                                    'port'                  =>  $server->getPort(),
                                    'service'               =>  $server->getService(),
                                    'uuid'                  =>  $server->getUuid(),
                                ],
                            ],
                            'state'                         =>  $taskExecution->getState(),
                            'updated_at'                    =>  $taskExecution->updated_at->toDateTimeLocalString('microsecond') . 'Z',
                            'uuid'                          =>  $taskModel->getUuid(),
                        ],
                    ],
                    'updated_at'                            =>  $pipelineExecution->updated_at->toDateTimeLocalString('microsecond') . 'Z',
                ],
            ],
            'message'                                                   =>  'Successfully fetched list of pipeline executions',
        ];
    }

    private function validatePipelineExecutionResponse(bool $useIdentifier = false, int $actionState = ExecutionState::CREATED): void
    {
        [
            $task,
            $action,
            $server,
            $pipeline,
            $pipelineExecution,
            $pipelineExecutionIdentifier,
            $taskExecution
        ] = $this->prepareDatabaseForPipelineExecutionTests($useIdentifier);

        $validationJson = $this->buildJsonForExecutionResponseValidation(
            $pipeline,
            $pipelineExecution,
            $task,
            $taskExecution,
            $server,
            $action,
            $actionState
        );

        $response = $this->get('/task-manager/pipelines/' . $pipelineExecutionIdentifier . '/execution');
        $response->assertStatus(200);

        $response->assertExactJson($validationJson);
    }

    private function prepareDatabaseForPipelineExecutionTests(bool $useIdentifier = false): array
    {
        $this->createServiceThroughRepository();

        $pipelineResponse = $this->createAndGetPipeline();
        $pipelineResponse->assertStatus(201);

        $taskResponse = $this->createAndGetTask();
        $taskResponse->assertStatus(201);
        $task = Task::uuid(Arr::get($taskResponse->json('data'), 'uuid'));

        $actionResponse = $this->createAndGetAction();
        $actionResponse->assertStatus(201);
        $action = Action::uuid(Arr::get($actionResponse->json('data'), 'uuid'));

        $server = Service::uuid($this->serverIdentifier);

        $pipelineData = $pipelineResponse->json('data');
        $pipeline = Pipeline::uuid(Arr::get($pipelineData, 'uuid'));
        $pipelineExecution = $this->createAndGetPipelineExecution();
        $pipelineExecutionIdentifier = $useIdentifier ? $pipelineExecution->getID() : $pipelineExecution->getUuid();


        $taskExecution = $this->createAndGetTaskExecution();

        return [
            $task,
            $action,
            $server,
            $pipeline,
            $pipelineExecution,
            $pipelineExecutionIdentifier,
            $taskExecution,
        ];
    }

    private function buildJsonForExecutionResponseValidation(
        PipelineInterface $pipelineModel,
        PipelineExecutionInterface $pipelineExecution,
        TaskInterface $taskModel,
        TaskExecutionInterface $taskExecution,
        ServiceInterface $serverModel,
        ActionInterface $actionModel,
        int $actionState,
    ): array {
        $pipeline = $this->generateResponseForPipeline(
            $pipelineModel,
            $pipelineExecution,
        );

        $task = $this->generateResponseForTask(
            $taskModel,
            $taskExecution
        );

        $server = $this->generateResponseForServer(
            $serverModel,
        );

        $actionExecution = $this->createAndGetActionExecution($actionState);

        switch ($actionState) {
            case ExecutionState::CREATED:
                $server['actions'][] = $this->generateResponseForCreatedAction(
                    $actionModel,
                    $actionExecution,
                );
                break;
            case ExecutionState::EXECUTING:
                $server['actions'][] = $this->generateResponseForRunningAction(
                    $actionModel,
                    $actionExecution,
                    $serverModel,
                );
                break;
            case ExecutionState::SUCCESS:
                $server['actions'][] = $this->generateResponseForFinishedAction(
                    $actionModel,
                    $actionExecution
                );
                break;
        }

        $task['servers'][$serverModel->getUuid()] = $server;

        $pipeline['tasks'][] = $task;

        return [
            'success'                                                   =>  true,
            'code'                                                      =>  200,
            'data'                                                      =>  $pipeline,
            'message'                                   =>  'Successfully fetched pipeline execution information',
        ];
    }

    private function generateResponseForPipeline(PipelineInterface $pipeline, PipelineExecutionInterface $pipelineExecution): array
    {
        return [
            'execution'                     =>  [
                'created_at'                =>  $pipelineExecution->created_at->toDateTimeLocalString('microsecond') . 'Z',
                'deleted_at'                =>  null,
                'id'                        =>  $pipelineExecution->getID(),
                'pipeline_uuid'             =>  $pipelineExecution->getPipelineUuid(),
                'state'                     =>  $pipelineExecution->getState(),
                'updated_at'                =>  $pipelineExecution->updated_at->toDateTimeLocalString('microsecond') . 'Z',
                'uuid'                      =>  $pipelineExecution->getUuid(),
            ],
            'pipeline'                      =>  [
                'created_at'                =>  $pipeline->created_at->toDateTimeLocalString('microsecond') . 'Z',
                'deleted_at'                =>  null,
                'description'               =>  $pipeline->getDescription(),
                'id'                        =>  $pipeline->getID(),
                'name'                      =>  $pipeline->getName(),
                'updated_at'                =>  $pipeline->updated_at->toDateTimeLocalString('microsecond') . 'Z',
                'uuid'                      =>  $pipeline->getUuid(),
            ],
            'tasks'                         =>  [],
        ];
    }

    private function generateResponseForTask(TaskInterface $task, TaskExecutionInterface $taskExecution): array
    {
        return [
            'execution'                         =>  [
                'created_at'                    =>  $taskExecution->created_at->toDateTimeLocalString('microsecond') . 'Z',
                'id'                            =>  $taskExecution->getID(),
                'pipeline_execution_uuid'       =>  $taskExecution->getPipelineExecutionUuid(),
                'pipeline_uuid'                 =>  $taskExecution->getPipelineUuid(),
                'state'                         =>  $taskExecution->getState(),
                'task_uuid'                     =>  $taskExecution->getTaskUuid(),
                'updated_at'                    =>  $taskExecution->updated_at->toDateTimeLocalString('microsecond') . 'Z',
            ],
            'servers'                           =>  [],
            'task'                              =>  [
                'created_at'                    =>  $task->created_at->toDateTimeLocalString('microsecond') . 'Z',
                'deleted_at'                    =>  null,
                'description'                   =>  $task->getDescription(),
                'fail_on_error'                 =>  $task->isFailingOnError(),
                'id'                            =>  $task->getID(),
                'name'                          =>  $task->getName(),
                'type'                          =>  $task->getType(),
                'updated_at'                    =>  $task->updated_at->toDateTimeLocalString('microsecond') . 'Z',
                'uuid'                          =>  $task->getUuid(),
            ],
        ];
    }

    private function generateResponseForServer(ServiceInterface $server): array
    {
        return [
            'actions'           =>  [],
            'address'           =>  $server->getAddress(),
            'datacenter'        =>  $server->getDatacenter(),
            'environment'       =>  $server->getEnvironment(),
            'identifier'        =>  $server->getIdentifier(),
            'port'              =>  $server->getPort(),
            'service'           =>  $server->getService(),
            'uuid'              =>  $server->getUuid(),
        ];
    }

    private function generateResponseForCreatedAction(
        ActionInterface $action,
        ActionExecutionInterface $actionExecution,
    ): array {
        return array_merge(
            $this->generateResponseForBaseAction($action, $actionExecution),
            [
                'log'                           =>  null,
            ]
        );
    }

    private function generateResponseForRunningAction(
        ActionInterface $action,
        ActionExecutionInterface $actionExecution,
        ServiceInterface $server,
    ): array {
        return array_merge(
            $this->generateResponseForBaseAction($action, $actionExecution),
            [
                'log'                           =>  $this->generateLogEndpoint(
                    $actionExecution,
                    $server
                ),
            ]
        );
    }

    private function generateResponseForFinishedAction(
        ActionInterface $action,
        ActionExecutionInterface $actionExecution
    ): array {
        $log = ActionExecutionLog::create([
            'action_execution_id'       =>  $actionExecution->getID(),
            'exit_code'                 =>  0,
            'output'                    =>  [],
        ]);

        return array_merge(
            $this->generateResponseForBaseAction($action, $actionExecution),
            [
                'log'                           =>  [
                    'action_execution_id'       =>  $log->getActionExecutionID(),
                    'created_at'                =>  $log->created_at->toDateTimeLocalString('microsecond') . 'Z',
                    'exit_code'                 =>  $log->getExitCode(),
                    'id'                        =>  $log->getID(),
                    'output'                    =>  $log->getOutput(),
                    'updated_at'                =>  $log->updated_at->toDateTimeLocalString('microsecond') . 'Z',
                ],
            ]
        );
    }

    /**
     * Generate response for base action
     * @param ActionInterface $action
     * @param ActionExecutionInterface $actionExecution
     * @return array[]
     */
    private function generateResponseForBaseAction(ActionInterface $action, ActionExecutionInterface $actionExecution): array
    {
        return [
            'action'                        =>  [
                'description'               =>  $action->getDescription(),
                'id'                        =>  $action->getID(),
                'name'                      =>  $action->getName(),
                'uuid'                      =>  $action->getUuid(),
            ],
            'execution'                     =>  [
                'action_uuid'               =>  $actionExecution->getActionUuid(),
                'created_at'                =>  $actionExecution->created_at->toDateTimeLocalString('microsecond') . 'Z',
                'id'                        =>  $actionExecution->getID(),
                'pipeline_execution_uuid'   =>  $actionExecution->getPipelineExecutionUuid(),
                'pipeline_uuid'             =>  $actionExecution->getPipelineUuid(),
                'server_uuid'               =>  $actionExecution->getServerUuid(),
                'state'                     =>  $actionExecution->getState(),
                'task_uuid'                 =>  $actionExecution->getTaskUuid(),
                'updated_at'                =>  $actionExecution->updated_at->toDateTimeLocalString('microsecond') . 'Z',
            ],
        ];
    }

    /**
     * Generate live log endpoint
     * @param ActionExecutionInterface $actionExecution
     * @param ServiceInterface $server
     * @return string
     */
    private function generateLogEndpoint(ActionExecutionInterface $actionExecution, ServiceInterface $server): string
    {
        $identifier = hash('sha256', sprintf(
            '%s_%s_%s_%s_%s',
            $actionExecution->getPipelineExecutionUuid(),
            $actionExecution->getPipelineUuid(),
            $actionExecution->getTaskUuid(),
            $actionExecution->getActionUuid(),
            $actionExecution->getServerUuid(),
        ));
        return sprintf(
            'http://%s:%d/events/watch?stream=%s',
            $server->getAddress(),
            $server->getPort(),
            $identifier,
        );
    }
}
