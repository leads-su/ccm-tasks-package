<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Update;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskRepositoryInterface;

/**
 * Class PipelineTaskUpdateInteractor
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Update
 */
class PipelineTaskUpdateInteractor implements PipelineTaskUpdateInputPort
{
    /**
     * Output port instance
     * @var PipelineTaskUpdateOutputPort
     */
    private PipelineTaskUpdateOutputPort $output;

    /**
     * Repository instance
     * @var PipelineTaskRepositoryInterface
     */
    private PipelineTaskRepositoryInterface $repository;

    /**
     * PipelineTaskUpdateInteractor constructor.
     * @param PipelineTaskUpdateOutputPort $output
     * @param PipelineTaskRepositoryInterface $repository
     * @return void
     */
    public function __construct(PipelineTaskUpdateOutputPort $output, PipelineTaskRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function update(PipelineTaskUpdateRequestModel $requestModel): ViewModel
    {
        $request = $requestModel->getRequest();

        try {
            $this->repository->update(
                pipelineIdentifier: $requestModel->getPipelineIdentifier(),
                taskIdentifier: $requestModel->getTaskIdentifier(),
                order: $request->get('order'),
            );
            return $this->output->update(new PipelineTaskUpdateResponseModel());
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new PipelineTaskUpdateResponseModel(), $exception->getModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new PipelineTaskUpdateResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
