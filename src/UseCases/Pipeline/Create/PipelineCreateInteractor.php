<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Create;

use Throwable;
use Illuminate\Support\Arr;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskRepositoryInterface;

/**
 * Class PipelineCreateInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Create
 */
class PipelineCreateInteractor implements PipelineCreateInputPort
{
    /**
     * Output port instance
     * @var PipelineCreateOutputPort
     */
    private PipelineCreateOutputPort $output;

    /**
     * Repository instance
     * @var PipelineRepositoryInterface
     */
    private PipelineRepositoryInterface $repository;

    /**
     * Pipeline Task repository interface
     * @var PipelineTaskRepositoryInterface
     */
    private PipelineTaskRepositoryInterface $pipelineTaskRepository;

    /**
     * PipelineCreateInteractor constructor.
     * @param PipelineCreateOutputPort $output
     * @param PipelineRepositoryInterface $repository
     * @param PipelineTaskRepositoryInterface $pipelineTaskRepository
     */
    public function __construct(
        PipelineCreateOutputPort $output,
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
    public function create(PipelineCreateRequestModel $requestModel): ViewModel
    {
        $request = $requestModel->getRequest();

        try {
            $entity = $this->repository->create(
                $request->get('name'),
                $request->get('description'),
            );
            $this->createTasksRelations($entity, $request->get('tasks', []));
            return $this->output->create(new PipelineCreateResponseModel($entity));
            // @codeCoverageIgnoreStart
        } catch (Throwable $exception) {
            return $this->output->internalServerError(new PipelineCreateResponseModel(), $exception);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Create relations between pipeline and tasks
     * @param PipelineInterface $pipeline
     * @param array $tasks
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function createTasksRelations(PipelineInterface $pipeline, array $tasks): void
    {
        $pipelineIdentifier = $pipeline->getUuid();

        foreach ($tasks as $index => $task) {
            $taskIdentifier = Arr::get($task, 'uuid');
            $order = $index + 1;

            $this->pipelineTaskRepository->create(
                pipelineIdentifier: $pipelineIdentifier,
                taskIdentifier: $taskIdentifier,
                order: $order,
            );
        }
    }
}
