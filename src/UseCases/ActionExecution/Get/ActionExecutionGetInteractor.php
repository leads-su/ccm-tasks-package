<?php

namespace ConsulConfigManager\Tasks\UseCases\ActionExecution\Get;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
     * ActionExecutionGetInteractor constructor.
     * @param ActionExecutionGetOutputPort $output
     * @param ActionExecutionRepositoryInterface $repository
     * @return void
     */
    public function __construct(ActionExecutionGetOutputPort $output, ActionExecutionRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function get(ActionExecutionGetRequestModel $requestModel): ViewModel
    {
        try {
            $execution = $this->repository->findByManyFromMappingsOrFail(
                mappings: [
                    'id'                =>  $requestModel->getExecution(),
                    'action_uuid'       =>  $requestModel->getIdentifier(),
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
