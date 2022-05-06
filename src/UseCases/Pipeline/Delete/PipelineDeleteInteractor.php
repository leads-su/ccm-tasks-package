<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Delete;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;

/**
 * Class PipelineDeleteInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Delete
 */
class PipelineDeleteInteractor implements PipelineDeleteInputPort
{
    /**
     * Output port instance
     * @var PipelineDeleteOutputPort
     */
    private PipelineDeleteOutputPort $output;

    /**
     * Repository instance
     * @var PipelineRepositoryInterface
     */
    private PipelineRepositoryInterface $repository;

    /**
     * PipelineDeleteInteractor constructor.
     * @param PipelineDeleteOutputPort $output
     * @param PipelineRepositoryInterface $repository
     * @return void
     */
    public function __construct(PipelineDeleteOutputPort $output, PipelineRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function delete(PipelineDeleteRequestModel $requestModel): ViewModel
    {
        try {
            $pipeline = $this->repository->findByManyOrFail(
                fields: ['id', 'uuid'],
                value: $requestModel->getIdentifier()
            );
            $this->repository->delete($pipeline->getID());
            return $this->output->delete(new PipelineDeleteResponseModel());
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new PipelineDeleteResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new PipelineDeleteResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
