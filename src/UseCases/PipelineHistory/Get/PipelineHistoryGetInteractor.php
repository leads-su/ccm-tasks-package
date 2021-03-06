<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineHistory\Get;

use Throwable;
use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Enums\ExecutionState;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionRepositoryInterface;
use ConsulConfigManager\Consul\Agent\Interfaces\ServiceRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineHistoryRepositoryInterface;

/**
 * Class PipelineHistoryGetInteractor
 * @package ConsulConfigManager\Tasks\UseCases\PipelineHistory\Get
 */
class PipelineHistoryGetInteractor implements PipelineHistoryGetInputPort
{
    /**
     * Output port instance
     * @var PipelineHistoryGetOutputPort
     */
    private PipelineHistoryGetOutputPort $output;

    /**
     * Pipeline repository instance
     * @var PipelineRepositoryInterface
     */
    private PipelineRepositoryInterface $pipelineRepository;

    /**
     * Repository instance
     * @var PipelineHistoryRepositoryInterface
     */
    private PipelineHistoryRepositoryInterface $pipelineExecutionRepository;

    /**
     * Task repository instance
     * @var TaskRepositoryInterface
     */
    private TaskRepositoryInterface $taskRepository;

    /**
     * Task Execution repository interface
     * @var TaskExecutionRepositoryInterface
     */
    private TaskExecutionRepositoryInterface $taskExecutionRepository;

    /**
     * Action repository instance
     * @var ActionRepositoryInterface
     */
    private ActionRepositoryInterface $actionRepository;

    /**
     * Action Execution repository interface
     * @var ActionExecutionRepositoryInterface
     */
    private ActionExecutionRepositoryInterface $actionExecutionRepository;

    /**
     * Service repository instance
     * @var ServiceRepositoryInterface
     */
    private ServiceRepositoryInterface $serviceRepository;

    /**
     * PipelineHistoryGetInteractor constructor.
     * @param PipelineHistoryGetOutputPort $output
     * @param PipelineRepositoryInterface $pipelineRepository
     * @param PipelineHistoryRepositoryInterface $pipelineExecutionRepository
     * @param TaskRepositoryInterface $taskRepository
     * @param TaskExecutionRepositoryInterface $taskExecutionRepository
     * @param ActionRepositoryInterface $actionRepository
     * @param ActionExecutionRepositoryInterface $actionExecutionRepository
     * @param ServiceRepositoryInterface $serviceRepository
     */
    public function __construct(
        PipelineHistoryGetOutputPort $output,
        PipelineRepositoryInterface $pipelineRepository,
        PipelineHistoryRepositoryInterface $pipelineExecutionRepository,
        TaskRepositoryInterface $taskRepository,
        TaskExecutionRepositoryInterface $taskExecutionRepository,
        ActionRepositoryInterface $actionRepository,
        ActionExecutionRepositoryInterface $actionExecutionRepository,
        ServiceRepositoryInterface $serviceRepository,
    ) {
        $this->output = $output;
        $this->pipelineRepository = $pipelineRepository;
        $this->pipelineExecutionRepository = $pipelineExecutionRepository;
        $this->taskRepository = $taskRepository;
        $this->taskExecutionRepository = $taskExecutionRepository;
        $this->actionRepository = $actionRepository;
        $this->actionExecutionRepository = $actionExecutionRepository;
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * @inheritDoc
     */
    public function get(PipelineHistoryGetRequestModel $requestModel): ViewModel
    {
        try {
            $execution = $this->pipelineExecutionRepository->findByManyOrFail(
                fields: ['id', 'uuid'],
                value: $requestModel->getIdentifier()
            );

            return $this->output->get(new PipelineHistoryGetResponseModel(
                $this->generatePipelineHistoryInformation(
                    $execution
                )
            ));
        } catch (Throwable $throwable) {
            if ($throwable instanceof ModelNotFoundException) {
                return $this->output->notFound(new PipelineHistoryGetResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new PipelineHistoryGetResponseModel(), $throwable);
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Generate information for this pipeline execution
     * @param PipelineExecutionInterface $pipelineExecution
     * @return array
     */
    private function generatePipelineHistoryInformation(PipelineExecutionInterface $pipelineExecution): array
    {
        $pipelineExecutionIdentifier = $pipelineExecution->getUuid();
        $pipeline = $this->pipelineRepository->findByOrFail('uuid', $pipelineExecution->getPipelineUuid());


        $information = [
            'pipeline'      =>  $pipeline->toArray(),
            'execution'     =>  $pipelineExecution->toArray(),
            'tasks'         =>  [],
        ];

        $taskExecutions = $this->taskExecutionRepository->findManyBy(
            'pipeline_execution_uuid',
            $pipelineExecutionIdentifier
        );

        foreach ($taskExecutions as $taskExecution) {
            $taskModel = $this->taskRepository->findByOrFail(
                'uuid',
                $taskExecution->getTaskUuid(),
            );

            $task = [
                'task'      =>  $taskModel->toArray(),
                'execution' =>  $taskExecution->toArray(),
                'servers'   =>  [],
            ];

            $actionExecutions = $this->actionExecutionRepository->findManyByMany([
                'pipeline_execution_uuid'   =>  $pipelineExecutionIdentifier,
                'task_uuid'                 =>  $taskModel->getUuid(),
            ]);

            /**
             * @var ActionExecutionInterface $actionExecution
             */
            foreach ($actionExecutions as $actionExecution) {
                $serverIdentifier = $actionExecution->getServerUuid();
                if (!isset($task['servers'][$serverIdentifier])) {
                    $service = $this->serviceRepository->findBy('uuid', $actionExecution->getServerUuid(), [
                        'uuid', 'identifier',
                        'service', 'address',
                        'port', 'datacenter',
                        'environment',
                    ]);

                    $task['servers'][$serverIdentifier] = array_merge(
                        $service->toArray(),
                        ['actions' => []]
                    );
                }

                $actionModel = $this->actionRepository->findByOrFail(
                    field: 'uuid',
                    value: $actionExecution->getActionUuid(),
                    columns: [
                        'id', 'uuid',
                        'name', 'description',
                    ]
                );

                $action = [
                    'action'        =>  $actionModel->toArray(),
                    'execution'     =>  $actionExecution->toArray(),
                ];

                $executionState = $actionExecution->getState();

                if (!in_array($executionState, [ ExecutionState::CREATED, ExecutionState::WAITING, ExecutionState::EXECUTING ])) {
                    $action['log'] = $actionExecution->log;
                } else {
                    if (!in_array($executionState, [ ExecutionState::CREATED, ExecutionState::CANCELED ])) {
                        $identifier = hash('sha256', sprintf(
                            '%s_%s_%s_%s_%s',
                            $actionExecution->getPipelineExecutionUuid(),
                            $actionExecution->getPipelineUuid(),
                            $actionExecution->getTaskUuid(),
                            $actionExecution->getActionUuid(),
                            $actionExecution->getServerUuid(),
                        ));
                        $action['log'] = sprintf(
                            'http://%s:%d/events/watch?stream=%s',
                            Arr::get($task['servers'][$serverIdentifier], 'address'),
                            Arr::get($task['servers'][$serverIdentifier], 'port'),
                            $identifier,
                        );
                    } else {
                        $action['log'] = null;
                    }
                }

                $task['servers'][$serverIdentifier]['actions'][] = $action;
            }

            $information['tasks'][] = $task;
        }

        return $information;
    }
}
