<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\Create;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Tasks\Exceptions\ModelAlreadyExistsException;
use ConsulConfigManager\Tasks\Interfaces\PipelinePermissionRepositoryInterface;

/**
 * Class PipelinePermissionCreateInteractor
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\Create
 */
class PipelinePermissionCreateInteractor implements PipelinePermissionCreateInputPort
{
    /**
     * Output port instance
     * @var PipelinePermissionCreateOutputPort
     */
    private PipelinePermissionCreateOutputPort $output;

    /**
     * Repository instance
     * @var PipelinePermissionRepositoryInterface
     */
    private PipelinePermissionRepositoryInterface $repository;

    /**
     * PipelinePermissionCreateInteractor constructor.
     * @param PipelinePermissionCreateOutputPort $output
     * @param PipelinePermissionRepositoryInterface $repository
     * @return void
     */
    public function __construct(PipelinePermissionCreateOutputPort $output, PipelinePermissionRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function create(PipelinePermissionCreateRequestModel $requestModel): ViewModel
    {
        try {
            return $this->output->create(new PipelinePermissionCreateResponseModel(
                $this->repository->create($requestModel->getUserIdentifier(), $requestModel->getPipelineIdentifier())
            ));
        } catch (Throwable $throwable) {
            if ($throwable instanceof ModelAlreadyExistsException) {
                return $this->output->alreadyExists(new PipelinePermissionCreateResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new PipelinePermissionCreateResponseModel(), $throwable);
            // @codeCoverageIgnoreEnd
        }
    }
}
