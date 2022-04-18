<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineExecution\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;
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
     * PipelineExecutionListInteractor constructor.
     * @param PipelineExecutionListOutputPort $output
     * @param PipelineExecutionRepositoryInterface $repository
     * @param PipelineRepositoryInterface $pipelineRepository
     */
    public function __construct(
        PipelineExecutionListOutputPort $output,
        PipelineExecutionRepositoryInterface $repository,
        PipelineRepositoryInterface $pipelineRepository,
    ) {
        $this->output = $output;
        $this->repository = $repository;
        $this->pipelineRepository = $pipelineRepository;
    }

    /**
     * @inheritDoc
     */
    public function list(PipelineExecutionListRequestModel $requestModel): ViewModel
    {
        try {
            $task = $this->pipelineRepository->findByManyOrFail(
                fields: [
                    'id',
                    'uuid',
                ],
                value: $requestModel->getIdentifier()
            );

            $executions = $this->repository->findManyBy(
                field: 'pipeline_uuid',
                value: $task->getUuid()
            );

            return $this->output->list(new PipelineExecutionListResponseModel(
                $executions->sortByDesc('id')->values()
            ));
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new PipelineExecutionListResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new PipelineExecutionListResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
