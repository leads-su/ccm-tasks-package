<?php

namespace ConsulConfigManager\Tasks\UseCases\ActionExecution\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionRepositoryInterface;

/**
 * Class ActionExecutionListInteractor
 * @package ConsulConfigManager\Tasks\UseCases\ActionExecution\List
 */
class ActionExecutionListInteractor implements ActionExecutionListInputPort
{
    /**
     * Output port instance
     * @var ActionExecutionListOutputPort
     */
    private ActionExecutionListOutputPort $output;

    /**
     * Repository instance
     * @var ActionExecutionRepositoryInterface
     */
    private ActionExecutionRepositoryInterface $repository;

    /**
     * ActionExecutionListInteractor constructor.
     * @param ActionExecutionListOutputPort $output
     * @param ActionExecutionRepositoryInterface $repository
     * @return void
     */
    public function __construct(ActionExecutionListOutputPort $output, ActionExecutionRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function list(ActionExecutionListRequestModel $requestModel): ViewModel
    {
        try {
            $executions = $this->repository->findManyBy(
                field: 'action_uuid',
                value: $requestModel->getIdentifier(),
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
                ]
            );
            return $this->output->list(new ActionExecutionListResponseModel(
                $executions->sortByDesc('id')->values()
            ));
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new ActionExecutionListResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new ActionExecutionListResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
