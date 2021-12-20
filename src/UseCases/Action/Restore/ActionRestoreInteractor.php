<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Restore;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;

/**
 * Class ActionRestoreInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Action\Restore
 */
class ActionRestoreInteractor implements ActionRestoreInputPort
{
    /**
     * Output port instance
     * @var ActionRestoreOutputPort
     */
    private ActionRestoreOutputPort $output;

    /**
     * Repository instance
     * @var ActionRepositoryInterface
     */
    private ActionRepositoryInterface $repository;

    /**
     * ActionRestoreInteractor constructor.
     * @param ActionRestoreOutputPort $output
     * @param ActionRepositoryInterface $repository
     * @return void
     */
    public function __construct(ActionRestoreOutputPort $output, ActionRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function restore(ActionRestoreRequestModel $requestModel): ViewModel
    {
        try {
            $action = $this->repository->findByManyOrFail(
                fields: ['id', 'uuid'],
                value: $requestModel->getIdentifier(),
                withDeleted: true,
            );
            $this->repository->restore($action->getID());
            return $this->output->restore(new ActionRestoreResponseModel());
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new ActionRestoreResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new ActionRestoreResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
