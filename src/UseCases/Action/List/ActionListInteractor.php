<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\List;

use Throwable;
use Illuminate\Support\Arr;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;

/**
 * Class ActionListInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Action\List
 */
class ActionListInteractor implements ActionListInputPort
{
    /**
     * Output port instance
     * @var ActionListOutputPort
     */
    private ActionListOutputPort $output;

    /**
     * Repository instance
     * @var ActionRepositoryInterface
     */
    private ActionRepositoryInterface $repository;

    /**
     * ActionListInteractor constructor.
     * @param ActionListOutputPort $output
     * @param ActionRepositoryInterface $repository
     * @return void
     */
    public function __construct(ActionListOutputPort $output, ActionRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function list(ActionListRequestModel $requestModel): ViewModel
    {
        try {
            $actions = $this->repository->all(
                columns: [
                    'id', 'uuid',
                    'name', 'description', 'type',
                    'created_at', 'updated_at', 'deleted_at',
                ],
                append: ['servers_extended'],
                withDeleted: $requestModel->getRequest()->get('with_deleted', false)
            )->map(function (ActionInterface $action): array {
                $action = $action->toArray();
                $action['servers'] = Arr::get($action, 'servers_extended');
                unset($action['servers_extended']);
                return $action;
            })->toArray();
            return $this->output->list(new ActionListResponseModel($actions));
            // @codeCoverageIgnoreStart
        } catch (Throwable $exception) {
            return $this->output->internalServerError(new ActionListResponseModel(), $exception);
        }
        // @codeCoverageIgnoreEnd
    }
}
