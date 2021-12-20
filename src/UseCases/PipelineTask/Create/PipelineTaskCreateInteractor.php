<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\Create;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Exceptions\ModelAlreadyExistsException;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskRepositoryInterface;

/**
 * Class PipelineTaskCreateInteractor
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\Create
 */
class PipelineTaskCreateInteractor implements PipelineTaskCreateInputPort
{
    /**
     * Output port instance
     * @var PipelineTaskCreateOutputPort
     */
    private PipelineTaskCreateOutputPort $output;

    /**
     * Repository instance
     * @var PipelineTaskRepositoryInterface
     */
    private PipelineTaskRepositoryInterface $repository;

    /**
     * PipelineTaskCreateInteractor constructor.
     * @param PipelineTaskCreateOutputPort $output
     * @param PipelineTaskRepositoryInterface $repository
     * @return void
     */
    public function __construct(PipelineTaskCreateOutputPort $output, PipelineTaskRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function create(PipelineTaskCreateRequestModel $requestModel): ViewModel
    {
        $request = $requestModel->getRequest();
        try {
            $this->repository->create(
                $requestModel->getPipelineIdentifier(),
                $request->get('task_uuid'),
                $request->get('order'),
            );
            return $this->output->create(new PipelineTaskCreateResponseModel($this->repository->get(
                $requestModel->getPipelineIdentifier(),
                $request->get('task_uuid')
            )));
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new PipelineTaskCreateResponseModel(), $exception->getModel());
            } elseif ($exception instanceof ModelAlreadyExistsException) {
                return $this->output->alreadyExists(new PipelineTaskCreateResponseModel(), $exception->getModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new PipelineTaskCreateResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
