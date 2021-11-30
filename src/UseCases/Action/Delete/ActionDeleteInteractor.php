<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Delete;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;

/**
 * Class ActionDeleteInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Action\Delete
 */
class ActionDeleteInteractor implements ActionDeleteInputPort
{
    /**
     * Output port instance
     * @var ActionDeleteOutputPort
     */
    private ActionDeleteOutputPort $output;

    /**
     * Repository instance
     * @var ActionRepositoryInterface
     */
    private ActionRepositoryInterface $repository;

    /**
     * ActionDeleteInteractor constructor.
     * @param ActionDeleteOutputPort $output
     * @param ActionRepositoryInterface $repository
     * @return void
     */
    public function __construct(ActionDeleteOutputPort $output, ActionRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function delete(ActionDeleteRequestModel $requestModel): ViewModel
    {
        try {
            $action = $this->repository->findByManyOrFail(['id', 'uuid'], $requestModel->getIdentifier());
            $this->repository->delete($action->getID());
            return $this->output->delete(new ActionDeleteResponseModel());
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new ActionDeleteResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new ActionDeleteResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
