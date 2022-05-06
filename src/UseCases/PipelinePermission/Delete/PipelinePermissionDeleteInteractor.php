<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\Delete;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\PipelinePermissionRepositoryInterface;

/**
 * Class PipelinePermissionDeleteInteractor
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\Delete
 */
class PipelinePermissionDeleteInteractor implements PipelinePermissionDeleteInputPort
{
    /**
     * Output port instance
     * @var PipelinePermissionDeleteOutputPort
     */
    private PipelinePermissionDeleteOutputPort $output;

    /**
     * Repository instance
     * @var PipelinePermissionRepositoryInterface
     */
    private PipelinePermissionRepositoryInterface $repository;

    /**
     * PipelinePermissionDeleteInteractor constructor.
     * @param PipelinePermissionDeleteOutputPort $output
     * @param PipelinePermissionRepositoryInterface $repository
     * @return void
     */
    public function __construct(PipelinePermissionDeleteOutputPort $output, PipelinePermissionRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function delete(PipelinePermissionDeleteRequestModel $requestModel): ViewModel
    {
        try {
            $this->repository->forceDelete($requestModel->getUserIdentifier(), $requestModel->getPipelineIdentifier());
            return $this->output->delete(new PipelinePermissionDeleteResponseModel());
        } catch (Throwable $throwable) {
            if ($throwable instanceof ModelNotFoundException) {
                return $this->output->notFound(new PipelinePermissionDeleteResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new PipelinePermissionDeleteResponseModel(), $throwable);
            // @codeCoverageIgnoreEnd
        }
    }
}
