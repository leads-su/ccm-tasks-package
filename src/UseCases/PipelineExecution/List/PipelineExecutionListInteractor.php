<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineExecution\List;

use Throwable;
use Illuminate\Support\Collection;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionRepositoryInterface;
use ConsulConfigManager\Consul\Agent\Interfaces\ServiceRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionRepositoryInterface;

/**
 * Class PipelineExecutionListInteractor
 * @package ConsulConfigManager\Tasks\UseCases\PipelineExecution\List
 */
class PipelineExecutionListInteractor implements PipelineExecutionListInputPort
{
    /**
     * Output port instance
     * @var PipelineExecutionListOutputPort
     */
    private PipelineExecutionListOutputPort $output;

    /**
     * Pipeline repository instance
     * @var PipelineRepositoryInterface
     */
    private PipelineRepositoryInterface $pipelineRepository;

    /**
     * Repository instance
     * @var PipelineExecutionRepositoryInterface
     */
    private PipelineExecutionRepositoryInterface $pipelineExecutionRepository;

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
     * PipelineExecutionListInteractor constructor.
     * @param PipelineExecutionListOutputPort $output
     * @param PipelineRepositoryInterface $pipelineRepository
     * @param PipelineExecutionRepositoryInterface $pipelineExecutionRepository
     * @param TaskRepositoryInterface $taskRepository
     * @param TaskExecutionRepositoryInterface $taskExecutionRepository
     * @param ActionRepositoryInterface $actionRepository
     * @param ActionExecutionRepositoryInterface $actionExecutionRepository
     * @param ServiceRepositoryInterface $serviceRepository
     */
    public function __construct(
        PipelineExecutionListOutputPort $output,
        PipelineRepositoryInterface $pipelineRepository,
        PipelineExecutionRepositoryInterface $pipelineExecutionRepository,
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
    public function list(PipelineExecutionListRequestModel $requestModel): ViewModel
    {
        try {
            $executions = $this->pipelineExecutionRepository->all(
                withDeleted: $requestModel->getRequest()->get('with_deleted', false)
            );
            return $this->output->list(new PipelineExecutionListResponseModel(
                $this->generatePipelineExecutionInformation(
                    $executions
                )
            ));
            // @codeCoverageIgnoreStart
        } catch (Throwable $throwable) {
            return $this->output->internalServerError(new PipelineExecutionListResponseModel(), $throwable);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Generate information about pipeline execution
     * @param EloquentCollection|PipelineExecutionInterface[] $executions
     * @return Collection
     */
    private function generatePipelineExecutionInformation(EloquentCollection $executions): Collection
    {
        $results = [];

        foreach ($executions as $pipelineIndex => $pipelineExecution) {
            $pipelineExecutionIdentifier = $pipelineExecution->getUuid();
            $pipeline = $this->pipelineRepository->findByOrFail('uuid', $pipelineExecution->getPipelineUuid());

            $executionInformation = [
                'id'                    =>  $pipelineExecution->getID(),
                'execution_uuid'        =>  $pipelineExecutionIdentifier,
                'pipeline_uuid'         =>  $pipeline->getUuid(),
                'name'                  =>  $pipeline->getName(),
                'state'                 =>  $pipelineExecution->getState(),
                'tasks'                 =>  [],
                'created_at'            =>  $pipelineExecution->created_at,
                'updated_at'            =>  $pipelineExecution->updated_at,
            ];

            $executionTasks = $this->taskExecutionRepository->findManyBy(
                'pipeline_execution_uuid',
                $pipelineExecutionIdentifier
            );

            foreach ($executionTasks as $taskIndex => $taskExecution) {
                $task = $this->taskRepository->findByOrFail(
                    'uuid',
                    $taskExecution->getTaskUuid(),
                );

                $executionInformation['tasks'][$taskIndex] = [
                    'id'            =>  $taskExecution->getID(),
                    'uuid'          =>  $task->getUuid(),
                    'name'          =>  $task->getName(),
                    'state'         =>  $taskExecution->getState(),
                    'servers'       =>  [],
                    'created_at'    =>  $taskExecution->created_at,
                    'updated_at'    =>  $taskExecution->updated_at,
                ];

                $executionActions = $this->actionExecutionRepository->findManyByMany([
                    'pipeline_execution_uuid'   =>  $pipelineExecutionIdentifier,
                    'task_uuid'                 =>  $task->getUuid(),
                ]);

                foreach ($executionActions as $actionExecution) {
                    $serverIdentifier = $actionExecution->getServerUuid();
                    if (!isset($executionInformation['tasks'][$taskIndex]['servers'][$serverIdentifier])) {
                        $service = $this->serviceRepository->findBy('uuid', $actionExecution->getServerUuid(), [
                            'uuid', 'identifier',
                            'service', 'address',
                            'port', 'datacenter',
                            'environment',
                        ]);
                        $executionInformation['tasks'][$taskIndex]['servers'][$serverIdentifier] = array_merge(
                            $service->toArray(),
                            ['actions' => []]
                        );
                    }

                    $action = $this->actionRepository->findByOrFail(
                        'uuid',
                        $actionExecution->getActionUuid(),
                    );

                    $executionInformation['tasks'][$taskIndex]['servers'][$serverIdentifier]['actions'][] = [
                        'id'            =>  $actionExecution->getID(),
                        'uuid'          =>  $action->getUuid(),
                        'name'          =>  $action->getName(),
                        'state'         =>  $actionExecution->getState(),
                        'created_at'    =>  $actionExecution->created_at,
                        'updated_at'    =>  $actionExecution->updated_at,
                    ];
                }
            }

            $results[$pipelineIndex] = $executionInformation;
        }

        return collect($results);
    }
}
