<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Restore;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;

/**
 * Class PipelineRestoreInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Restore
 */
class PipelineRestoreInteractor implements PipelineRestoreInputPort
{
    /**
     * Output port instance
     * @var PipelineRestoreOutputPort
     */
    private PipelineRestoreOutputPort $output;

    /**
     * Repository instance
     * @var PipelineRepositoryInterface
     */
    private PipelineRepositoryInterface $repository;

    /**
     * PipelineRestoreInteractor constructor.
     * @param PipelineRestoreOutputPort $output
     * @param PipelineRepositoryInterface $repository
     * @return void
     */
    public function __construct(PipelineRestoreOutputPort $output, PipelineRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function restore(PipelineRestoreRequestModel $requestModel): ViewModel
    {
        try {
            $pipeline = $this->repository->findByManyOrFail(
                fields: ['id', 'uuid'],
                value: $requestModel->getIdentifier(),
                withDeleted: true,
            );
            $this->repository->restore($pipeline->getID());
            return $this->output->restore(new PipelineRestoreResponseModel());
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new PipelineRestoreResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new PipelineRestoreResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
