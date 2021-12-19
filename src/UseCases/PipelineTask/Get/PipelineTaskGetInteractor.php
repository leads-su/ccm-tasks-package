<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Get;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskRepositoryInterface;

/**
 * Class PipelineTaskGetInteractor
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Get
 */
class PipelineTaskGetInteractor implements PipelineTaskGetInputPort
{
    /**
     * Output port instance
     * @var PipelineTaskGetOutputPort
     */
    private PipelineTaskGetOutputPort $output;

    /**
     * Action repository instance
     * @var PipelineTaskRepositoryInterface
     */
    private PipelineTaskRepositoryInterface $repository;

    /**
     * PipelineTaskGetInteractor constructor.
     * @param PipelineTaskGetOutputPort $output
     * @param PipelineTaskRepositoryInterface $repository
     */
    public function __construct(PipelineTaskGetOutputPort $output, PipelineTaskRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function information(PipelineTaskGetRequestModel $requestModel): ViewModel
    {
        try {
            $model = $this->repository->get(
                $requestModel->getPipelineIdentifier(),
                $requestModel->getTaskIdentifier(),
                [
                    'pipeline',
                    'task',
                ]
            );
            return $this->output->information(new PipelineTaskGetResponseModel($model));
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new PipelineTaskGetResponseModel(), $exception->getModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new PipelineTaskGetResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
