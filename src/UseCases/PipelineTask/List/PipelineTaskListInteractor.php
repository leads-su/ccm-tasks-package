<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelineTask\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskRepositoryInterface;

/**
 * Class PipelineTaskListInteractor
 * @package ConsulConfigManager\Tasks\UseCases\PipelineTask\List
 */
class PipelineTaskListInteractor implements PipelineTaskListInputPort
{
    /**
     * Output port instance
     * @var PipelineTaskListOutputPort
     */
    private PipelineTaskListOutputPort $output;

    /**
     * Repository instance
     * @var PipelineTaskRepositoryInterface
     */
    private PipelineTaskRepositoryInterface $repository;

    /**
     * PipelineTaskListInteractor constructor.
     * @param PipelineTaskListOutputPort $output
     * @param PipelineTaskRepositoryInterface $repository
     * @return void
     */
    public function __construct(PipelineTaskListOutputPort $output, PipelineTaskRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function list(PipelineTaskListRequestModel $requestModel): ViewModel
    {
        try {
            $actions = $this->repository->list(
                $requestModel->getIdentifier(),
                $requestModel->getRequest()->get('with_deleted', false)
            );
            return $this->output->list(new PipelineTaskListResponseModel($actions));
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new PipelineTaskListResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new PipelineTaskListResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
