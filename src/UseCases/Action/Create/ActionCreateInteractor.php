<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Create;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionHostRepositoryInterface;

/**
 * Class ActionCreateInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Action\Create
 */
class ActionCreateInteractor implements ActionCreateInputPort
{
    /**
     * Output port instance
     * @var ActionCreateOutputPort
     */
    private ActionCreateOutputPort $output;

    /**
     * Repository instance
     * @var ActionRepositoryInterface
     */
    private ActionRepositoryInterface $repository;

    /**
     * Action Host repository interface
     * @var ActionHostRepositoryInterface
     */
    private ActionHostRepositoryInterface $actionHostRepository;

    /**
     * ActionCreateInteractor constructor.
     * @param ActionCreateOutputPort $output
     * @param ActionRepositoryInterface $repository
     * @param ActionHostRepositoryInterface $actionHostRepository
     */
    public function __construct(
        ActionCreateOutputPort $output,
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
    public function create(ActionCreateRequestModel $requestModel): ViewModel
    {
        $request = $requestModel->getRequest();

        try {
            $entity = $this->repository->create(
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

            foreach ($request->get('servers', []) as $server) {
                $this->actionHostRepository->create(
                    $entity->getUuid(),
                    $server,
                );
            }

            return $this->output->create(new ActionCreateResponseModel($entity));
            // @codeCoverageIgnoreStart
        } catch (Throwable $exception) {
            return $this->output->internalServerError(new ActionCreateResponseModel(), $exception);
        }
        // @codeCoverageIgnoreEnd
    }
}
