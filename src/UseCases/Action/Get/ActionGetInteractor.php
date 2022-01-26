<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Get;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;

/**
 * Class ActionGetInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Action\Get
 */
class ActionGetInteractor implements ActionGetInputPort
{
    /**
     * Output port instance
     * @var ActionGetOutputPort
     */
    private ActionGetOutputPort $output;

    /**
     * Repository instance
     * @var ActionRepositoryInterface
     */
    private ActionRepositoryInterface $repository;

    /**
     * ActionGetInteractor constructor.
     * @param ActionGetOutputPort $output
     * @param ActionRepositoryInterface $repository
     * @return void
     */
    public function __construct(ActionGetOutputPort $output, ActionRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function get(ActionGetRequestModel $requestModel): ViewModel
    {
        try {
            $action = $this->repository->findByManyOrFail(
                fields: ['id', 'uuid'],
                value: $requestModel->getIdentifier(),
                append: ['history', 'servers'],
                withDeleted: $requestModel->getRequest()->get('with_deleted', false)
            );
            return $this->output->get(new ActionGetResponseModel($action));
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new ActionGetResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new ActionGetResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
