<?php

namespace ConsulConfigManager\Tasks\UseCases\Task\Create;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Tasks\Interfaces\TaskRepositoryInterface;

/**
 * Class TaskCreateInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Task\Create
 */
class TaskCreateInteractor implements TaskCreateInputPort
{
    /**
     * Output port instance
     * @var TaskCreateOutputPort
     */
    private TaskCreateOutputPort $output;

    /**
     * Repository instance
     * @var TaskRepositoryInterface
     */
    private TaskRepositoryInterface $repository;

    /**
     * TaskCreateInteractor constructor.
     * @param TaskCreateOutputPort $output
     * @param TaskRepositoryInterface $repository
     * @return void
     */
    public function __construct(
        TaskCreateOutputPort $output,
        TaskRepositoryInterface $repository,
    ) {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function create(TaskCreateRequestModel $requestModel): ViewModel
    {
        $request = $requestModel->getRequest();

        try {
            $entity = $this->repository->create(
                $request->get('name'),
                $request->get('description'),
                $request->get('type'),
            );
            return $this->output->create(new TaskCreateResponseModel($entity));
            // @codeCoverageIgnoreStart
        } catch (Throwable $exception) {
            return $this->output->internalServerError(new TaskCreateResponseModel(), $exception);
        }
        // @codeCoverageIgnoreEnd
    }
}
