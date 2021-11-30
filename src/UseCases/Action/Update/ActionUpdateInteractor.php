<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Update;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;

/**
 * Class ActionUpdateInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Action\Update
 */
class ActionUpdateInteractor implements ActionUpdateInputPort
{
    /**
     * Output port instance
     * @var ActionUpdateOutputPort
     */
    private ActionUpdateOutputPort $output;

    /**
     * Repository instance
     * @var ActionRepositoryInterface
     */
    private ActionRepositoryInterface $repository;

    /**
     * ActionUpdateInteractor constructor.
     * @param ActionUpdateOutputPort $output
     * @param ActionRepositoryInterface $repository
     * @return void
     */
    public function __construct(
        ActionUpdateOutputPort $output,
        ActionRepositoryInterface $repository,
    ) {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function update(ActionUpdateRequestModel $requestModel): ViewModel
    {
        $request = $requestModel->getRequest();

        try {
            $model = $this->repository->findByManyOrFail(['id', 'uuid'], $requestModel->getIdentifier());

            $entity = $this->repository->update(
                $model->getID(),
                $request->get('name'),
                $request->get('description'),
                $request->get('type'),
                $request->get('command'),
                $request->get('arguments'),
                $request->get('working_dir', null),
                $request->get('run_as', null),
                $request->get('use_sudo', false),
                $request->get('fail_on_error', true),
            );
            return $this->output->update(new ActionUpdateResponseModel($entity));
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new ActionUpdateResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new ActionUpdateResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
