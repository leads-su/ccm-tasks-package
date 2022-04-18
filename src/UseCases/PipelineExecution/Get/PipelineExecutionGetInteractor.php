<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineExecution\Get;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionRepositoryInterface;

/**
 * Class PipelineExecutionGetInteractor
 * @package ConsulConfigManager\Tasks\UseCases\PipelineExecution\Get
 */
class PipelineExecutionGetInteractor implements PipelineExecutionGetInputPort
{
    /**
     * Output port instance
     * @var PipelineExecutionGetOutputPort
     */
    private PipelineExecutionGetOutputPort $output;

    /**
     * Repository instance
     * @var PipelineExecutionRepositoryInterface
     */
    private PipelineExecutionRepositoryInterface $repository;

    /**
     * Task repository instance
     * @var PipelineRepositoryInterface
     */
    private PipelineRepositoryInterface $pipelineRepository;

    /**
     * PipelineExecutionGetInteractor constructor.
     * @param PipelineExecutionGetOutputPort $output
     * @param PipelineExecutionRepositoryInterface $repository
     * @param PipelineRepositoryInterface $pipelineRepository
     * @return void
     */
    public function __construct(PipelineExecutionGetOutputPort $output, PipelineExecutionRepositoryInterface $repository, PipelineRepositoryInterface $pipelineRepository)
    {
        $this->output = $output;
        $this->repository = $repository;
        $this->pipelineRepository = $pipelineRepository;
    }

    /**
     * @inheritDoc
     */
    public function get(PipelineExecutionGetRequestModel $requestModel): ViewModel
    {
        try {
            $pipeline = $this->pipelineRepository->findByManyOrFail(
                fields: [
                    'id',
                    'uuid',
                ],
                value: $requestModel->getIdentifier(),
            );

            $execution = $this->repository->findByManyFromMappingsOrFail(
                mappings: [
                    'id'            =>  $requestModel->getExecution(),
                    'pipeline_uuid' =>  $pipeline->getUuid(),
                ]
            );

            $execution = $execution->toArray();
            $execution['pipeline'] = $pipeline->toArray();
            $execution['tasks'] = $pipeline->tasks()->get([
                'tasks.id', 'tasks.uuid',
                'tasks.name', 'tasks.description',
            ])->toArray();

            return $this->output->get(new PipelineExecutionGetResponseModel(
                $execution,
            ));
        } catch (Throwable $throwable) {
            if ($throwable instanceof ModelNotFoundException) {
                return $this->output->notFound(new PipelineExecutionGetResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new PipelineExecutionGetResponseModel(), $throwable);
            // @codeCoverageIgnoreEnd
        }
    }
}
