<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Restore;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskRepositoryInterface;

/**
 * Class PipelineTaskRestoreInteractor
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Restore
 */
class PipelineTaskRestoreInteractor implements PipelineTaskRestoreInputPort
{
    /**
     * Output port instance
     * @var PipelineTaskRestoreOutputPort
     */
    private PipelineTaskRestoreOutputPort $output;

    /**
     * Repository instance
     * @var PipelineTaskRepositoryInterface
     */
    private PipelineTaskRepositoryInterface $repository;


    /**
     * PipelineTaskRestoreInteractor constructor.
     * @param PipelineTaskRestoreOutputPort $output
     * @param PipelineTaskRepositoryInterface $repository
     */
    public function __construct(PipelineTaskRestoreOutputPort $output, PipelineTaskRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function restore(PipelineTaskRestoreRequestModel $requestModel): ViewModel
    {
        try {
            $this->repository->restore($requestModel->getPipelineIdentifier(), $requestModel->getTaskIdentifier());
            return $this->output->restore(new PipelineTaskRestoreResponseModel());
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new PipelineTaskRestoreResponseModel(), $exception->getModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new PipelineTaskRestoreResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
