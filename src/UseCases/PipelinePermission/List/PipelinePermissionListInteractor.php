<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Users\Interfaces\UserRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelinePermissionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelinePermissionRepositoryInterface;

/**
 * Class PipelinePermissionListInteractor
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\List
 */
class PipelinePermissionListInteractor implements PipelinePermissionListInputPort
{
    /**
     * Output port instance
     * @var PipelinePermissionListOutputPort
     */
    private PipelinePermissionListOutputPort $output;

    /**
     * Repository instance
     * @var PipelinePermissionRepositoryInterface
     */
    private PipelinePermissionRepositoryInterface $repository;

    /**
     * Pipeline repository instance
     * @var PipelineRepositoryInterface
     */
    private PipelineRepositoryInterface $pipelineRepository;

    /**
     * User repository instance
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * PipelinePermissionListInteractor constructor.
     * @param PipelinePermissionListOutputPort $output
     * @param PipelinePermissionRepositoryInterface $repository
     * @param PipelineRepositoryInterface $pipelineRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        PipelinePermissionListOutputPort $output,
        PipelinePermissionRepositoryInterface $repository,
        PipelineRepositoryInterface $pipelineRepository,
        UserRepositoryInterface $userRepository,
    ) {
        $this->output = $output;
        $this->repository = $repository;
        $this->pipelineRepository = $pipelineRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritDoc
     */
    public function list(PipelinePermissionListRequestModel $requestModel): ViewModel
    {
        try {
            $user = $this->userRepository->find($requestModel->getIdentifier(), [
                'id',
                'first_name',
                'last_name',
                'username',
                'email',
            ]);

            if (!$user) {
                return $this->output->notFound(new PipelinePermissionListResponseModel());
            }

            $pipelines = [];

            foreach ($this->pipelineRepository->all(['uuid', 'name', 'description']) as $pipeline) {
                $pipelines[$pipeline->getUuid()] = [
                    'uuid'          =>  $pipeline->getUuid(),
                    'name'          =>  $pipeline->getName(),
                    'description'   =>  $pipeline->getDescription(),
                ];
            }

            return $this->output->list(new PipelinePermissionListResponseModel([
                'user'          =>  $user->toArray(),
                'permissions'   =>  $this->repository
                    ->findManyBy(
                        field: 'user_id',
                        value: $requestModel->getIdentifier(),
                        columns: ['pipeline_uuid']
                    )->map(function (PipelinePermissionInterface $entity) use ($pipelines): array {
                        return $pipelines[$entity->getPipelineUuid()];
                    }),
            ]));
            // @codeCoverageIgnoreStart
        } catch (Throwable $throwable) {
            return $this->output->internalServerError(new PipelinePermissionListResponseModel(), $throwable);
        }
        // @codeCoverageIgnoreEnd
    }
}
