<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Create;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;

/**
 * Class PipelineCreateInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Create
 */
class PipelineCreateInteractor implements PipelineCreateInputPort
{
    /**
     * Output port instance
     * @var PipelineCreateOutputPort
     */
    private PipelineCreateOutputPort $output;

    /**
     * Repository instance
     * @var PipelineRepositoryInterface
     */
    private PipelineRepositoryInterface $repository;

    /**
     * PipelineCreateInteractor constructor.
     * @param PipelineCreateOutputPort $output
     * @param PipelineRepositoryInterface $repository
     * @return void
     */
    public function __construct(
        PipelineCreateOutputPort $output,
        PipelineRepositoryInterface $repository,
    ) {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function create(PipelineCreateRequestModel $requestModel): ViewModel
    {
        $request = $requestModel->getRequest();

        try {
            $entity = $this->repository->create(
                $request->get('name'),
                $request->get('description'),
            );
            return $this->output->create(new PipelineCreateResponseModel($entity));
            // @codeCoverageIgnoreStart
        } catch (Throwable $exception) {
            return $this->output->internalServerError(new PipelineCreateResponseModel(), $exception);
        }
        // @codeCoverageIgnoreEnd
    }
}
