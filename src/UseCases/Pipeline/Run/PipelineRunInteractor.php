<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Run;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Tasks\Jobs\PipelineRunnerJob;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;

/**
 * Class PipelineRunInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Run
 */
class PipelineRunInteractor implements PipelineRunInputPort
{
    /**
     * Output port instance
     * @var PipelineRunOutputPort
     */
    private PipelineRunOutputPort $output;

    /**
     * Repository instance
     * @var PipelineRepositoryInterface
     */
    private PipelineRepositoryInterface $repository;

    /**
     * PipelineRunInteractor constructor.
     * @param PipelineRunOutputPort $output
     * @param PipelineRepositoryInterface $repository
     * @return void
     */
    public function __construct(PipelineRunOutputPort $output, PipelineRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function run(PipelineRunRequestModel $requestModel): ViewModel
    {
        try {
            $pipeline = $this->repository->findByManyOrFail(
                fields: ['id', 'uuid'],
                value: $requestModel->getIdentifier(),
            );

            PipelineRunnerJob::dispatch(($pipeline->getUuid()));

            return $this->output->run(new PipelineRunResponseModel());
        } catch (Throwable $throwable) {
            if ($throwable instanceof ModelNotFoundException) {
                return $this->output->notFound(new PipelineRunResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new PipelineRunResponseModel(), $throwable);
            // @codeCoverageIgnoreEnd
        }
    }
}
