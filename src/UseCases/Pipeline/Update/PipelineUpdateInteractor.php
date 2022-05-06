<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Update;

use Throwable;
use Illuminate\Support\Arr;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskRepositoryInterface;

/**
 * Class PipelineUpdateInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Update
 */
class PipelineUpdateInteractor implements PipelineUpdateInputPort
{
    /**
     * Output port instance
     * @var PipelineUpdateOutputPort
     */
    private PipelineUpdateOutputPort $output;

    /**
     * Repository instance
     * @var PipelineRepositoryInterface
     */
    private PipelineRepositoryInterface $repository;

    /**
     * Pipeline Task repository instance
     * @var PipelineTaskRepositoryInterface
     */
    private PipelineTaskRepositoryInterface $pipelineTaskRepository;

    /**
     * PipelineUpdateInteractor constructor.
     * @param PipelineUpdateOutputPort $output
     * @param PipelineRepositoryInterface $repository
     * @param PipelineTaskRepositoryInterface $pipelineTaskRepository
     */
    public function __construct(
        PipelineUpdateOutputPort $output,
        PipelineRepositoryInterface $repository,
        PipelineTaskRepositoryInterface $pipelineTaskRepository,
    ) {
        $this->output = $output;
        $this->repository = $repository;
        $this->pipelineTaskRepository = $pipelineTaskRepository;
    }

    /**
     * @inheritDoc
     */
    public function update(PipelineUpdateRequestModel $requestModel): ViewModel
    {
        $request = $requestModel->getRequest();

        try {
            $model = $this->repository->findByManyOrFail(
                fields: ['id', 'uuid'],
                value: $requestModel->getIdentifier()
            );

            $entity = $this->repository->update(
                $model->getID(),
                $request->get('name'),
                $request->get('description'),
            );

            $this->createOrUpdateTasksRelations($entity, $request->get('tasks', []));
            return $this->output->update(new PipelineUpdateResponseModel($entity));
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new PipelineUpdateResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new PipelineUpdateResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Create or update relations between pipeline and tasks
     * @param PipelineInterface $pipeline
     * @param array $tasks
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function createOrUpdateTasksRelations(PipelineInterface $pipeline, array $tasks): void
    {
        $pipelineIdentifier = $pipeline->getUuid();

        $databaseTasks = $this->pipelineTaskRepository
            ->getPipelineTasks($pipelineIdentifier)
            ->map(function (PipelineTaskInterface $pipelineTask): string {
                return $pipelineTask->getTaskUuid();
            })
            ->toArray();


        $processedTasks = [];

        foreach ($tasks as $index => $action) {
            $taskIdentifier = Arr::get($action, 'uuid');
            $processedTasks[] = $taskIdentifier;
            $order = $index + 1;

            if ($this->pipelineTaskRepository->exists($pipelineIdentifier, $taskIdentifier)) {
                $this->pipelineTaskRepository->update(
                    pipelineIdentifier: $pipelineIdentifier,
                    taskIdentifier: $taskIdentifier,
                    order: $order,
                );
            } else {
                $this->pipelineTaskRepository->create(
                    pipelineIdentifier: $pipelineIdentifier,
                    taskIdentifier: $taskIdentifier,
                    order: $order,
                );
            }
        }

        foreach (array_diff($databaseTasks, $processedTasks) as $action) {
            $this->pipelineTaskRepository->forceDelete($pipelineIdentifier, $action);
        }
    }
}
