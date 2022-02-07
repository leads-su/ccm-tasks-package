<?php

namespace ConsulConfigManager\Tasks\UseCases\ActionExecution\Get;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionRepositoryInterface;

/**
 * Class ActionExecutionGetInteractor
 * @package ConsulConfigManager\Tasks\UseCases\ActionExecution\Get
 */
class ActionExecutionGetInteractor implements ActionExecutionGetInputPort
{
    /**
     * Output port instance
     * @var ActionExecutionGetOutputPort
     */
    private ActionExecutionGetOutputPort $output;

    /**
     * Repository instance
     * @var ActionExecutionRepositoryInterface
     */
    private ActionExecutionRepositoryInterface $repository;

    /**
     * Action repository instance
     * @var ActionRepositoryInterface
     */
    private ActionRepositoryInterface $actionRepository;

    /**
     * ActionExecutionGetInteractor constructor.
     * @param ActionExecutionGetOutputPort $output
     * @param ActionExecutionRepositoryInterface $repository
     * @param ActionRepositoryInterface $actionRepository
     */
    public function __construct(
        ActionExecutionGetOutputPort $output,
        ActionExecutionRepositoryInterface $repository,
        ActionRepositoryInterface $actionRepository,
    ) {
        $this->output = $output;
        $this->repository = $repository;
        $this->actionRepository = $actionRepository;
    }

    /**
     * @inheritDoc
     */
    public function get(ActionExecutionGetRequestModel $requestModel): ViewModel
    {
        try {
            $action = $this->actionRepository->findByManyOrFail(
                fields: [
                    'id',
                    'uuid',
                ],
                value: $requestModel->getIdentifier()
            );

            $execution = $this->repository->findByManyFromMappingsOrFail(
                mappings: [
                    'id'                =>  $requestModel->getExecution(),
                    'action_uuid'       =>  $action->getUuid(),
                ],
                columns: [
                    'id',
                    'state',
                    'server_uuid',
                    'created_at',
                    'updated_at',
                ],
                with: [
                    'server'       =>  function ($query) {
                        $query->select(
                            'id',
                            'uuid',
                            'identifier',
                            'service',
                            'address',
                            'port',
                            'datacenter',
                            'environment'
                        );
                    },
                    'log',
                ]
            );

            return $this->output->get(new ActionExecutionGetResponseModel(
                $execution->toArray()
            ));
        } catch (Throwable $throwable) {
            if ($throwable instanceof ModelNotFoundException) {
                return $this->output->notFound(new ActionExecutionGetResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new ActionExecutionGetResponseModel(), $throwable);
            // @codeCoverageIgnoreEnd
        }
    }
}
