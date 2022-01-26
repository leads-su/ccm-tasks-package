<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Update;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionHostInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionHostRepositoryInterface;

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
     * Action Host repository instance
     * @var ActionHostRepositoryInterface
     */
    private ActionHostRepositoryInterface $actionHostRepository;

    /**
     * ActionUpdateInteractor constructor.
     * @param ActionUpdateOutputPort $output
     * @param ActionRepositoryInterface $repository
     * @param ActionHostRepositoryInterface $actionHostRepository
     */
    public function __construct(
        ActionUpdateOutputPort $output,
        ActionRepositoryInterface $repository,
        ActionHostRepositoryInterface $actionHostRepository,
    ) {
        $this->output = $output;
        $this->repository = $repository;
        $this->actionHostRepository = $actionHostRepository;
    }

    /**
     * @inheritDoc
     */
    public function update(ActionUpdateRequestModel $requestModel): ViewModel
    {
        $request = $requestModel->getRequest();

        try {
            $model = $this->repository->findByManyOrFail(
                fields: ['id', 'uuid'],
                value: $requestModel->getIdentifier()
            );

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
            $this->updateServersList($entity, $request->get('servers', []));
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

    /**
     * Update servers list for action
     * @param ActionInterface $action
     * @param array $servers
     * @return void
     */
    private function updateServersList(ActionInterface $action, array $servers = []): void
    {
        $existingServers = $this->actionHostRepository
            ->findByAction($action->getUuid())
            ->map(function (ActionHostInterface $actionHost): string {
                return $actionHost->getServiceUuid();
            })
            ->toArray();

        $removedServers = array_diff($existingServers, $servers);
        $addedServers = array_diff($servers, $existingServers);

        foreach ($removedServers as $server) {
            $this->actionHostRepository->delete($action->getUuid(), $server);
        }

        foreach ($addedServers as $server) {
            $this->actionHostRepository->create($action->getUuid(), $server);
        }
    }
}
