<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Delete;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskRepositoryInterface;

/**
 * Class PipelineTaskDeleteInteractor
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Delete
 */
class PipelineTaskDeleteInteractor implements PipelineTaskDeleteInputPort
{
    /**
     * Output port instance
     * @var PipelineTaskDeleteOutputPort
     */
    private PipelineTaskDeleteOutputPort $output;

    /**
     * Repository instance
     * @var PipelineTaskRepositoryInterface
     */
    private PipelineTaskRepositoryInterface $repository;


    /**
     * PipelineTaskDeleteInteractor constructor.
     * @param PipelineTaskDeleteOutputPort $output
     * @param PipelineTaskRepositoryInterface $repository
     */
    public function __construct(PipelineTaskDeleteOutputPort $output, PipelineTaskRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function delete(PipelineTaskDeleteRequestModel $requestModel): ViewModel
    {
        try {
            $this->repository->delete($requestModel->getPipelineIdentifier(), $requestModel->getTaskIdentifier());
            return $this->output->delete(new PipelineTaskDeleteResponseModel());
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new PipelineTaskDeleteResponseModel(), $exception->getModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new PipelineTaskDeleteResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
