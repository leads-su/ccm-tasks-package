<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;

/**
 * Class PipelineListInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\List
 */
class PipelineListInteractor implements PipelineListInputPort
{
    /**
     * Output port instance
     * @var PipelineListOutputPort
     */
    private PipelineListOutputPort $output;

    /**
     * Repository instance
     * @var PipelineRepositoryInterface
     */
    private PipelineRepositoryInterface $repository;

    /**
     * PipelineListInteractor constructor.
     * @param PipelineListOutputPort $output
     * @param PipelineRepositoryInterface $repository
     * @return void
     */
    public function __construct(PipelineListOutputPort $output, PipelineRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function list(PipelineListRequestModel $requestModel): ViewModel
    {
        try {
            $pipelines = $this->repository->all([
                'id', 'uuid',
                'name', 'description',
                'created_at', 'updated_at', 'deleted_at',
            ], $requestModel->getRequest()->get('with_deleted', false))->toArray();
            return $this->output->list(new PipelineListResponseModel($pipelines));
            // @codeCoverageIgnoreStart
        } catch (Throwable $exception) {
            return $this->output->internalServerError(new PipelineListResponseModel(), $exception);
        }
        // @codeCoverageIgnoreEnd
    }
}
