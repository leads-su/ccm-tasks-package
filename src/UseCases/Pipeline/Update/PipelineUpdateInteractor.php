<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Update;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;

/**
 * Class PipelineUpdateInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Update
 */
class PipelineUpdateInteractor implements PipelineUpdateInputPort
{
    /**
     * Output port instance
     * @var PipelineUpdateOutputPort
     */
    private PipelineUpdateOutputPort $output;

    /**
     * Repository instance
     * @var PipelineRepositoryInterface
     */
    private PipelineRepositoryInterface $repository;

    /**
     * PipelineUpdateInteractor constructor.
     * @param PipelineUpdateOutputPort $output
     * @param PipelineRepositoryInterface $repository
     * @return void
     */
    public function __construct(
        PipelineUpdateOutputPort $output,
        PipelineRepositoryInterface $repository,
    ) {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function update(PipelineUpdateRequestModel $requestModel): ViewModel
    {
        $request = $requestModel->getRequest();

        try {
            $model = $this->repository->findByManyOrFail(['id', 'uuid'], $requestModel->getIdentifier());

            $entity = $this->repository->update(
                $model->getID(),
                $request->get('name'),
                $request->get('description'),
            );
            return $this->output->update(new PipelineUpdateResponseModel($entity));
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new PipelineUpdateResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new PipelineUpdateResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
