<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\Users;

use Throwable;
use Illuminate\Support\Arr;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Users\Interfaces\UserRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelinePermissionRepositoryInterface;

/**
 * Class PipelinePermissionUsersInteractor
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\Users
 */
class PipelinePermissionUsersInteractor implements PipelinePermissionUsersInputPort
{
    /**
     * Output port instance
     * @var PipelinePermissionUsersOutputPort
     */
    private PipelinePermissionUsersOutputPort $output;

    /**
     * Repository instance
     * @var PipelinePermissionRepositoryInterface
     */
    private PipelinePermissionRepositoryInterface $repository;

    /**
     * Pipeline repository interface
     * @var PipelineRepositoryInterface
     */
    private PipelineRepositoryInterface $pipelineRepository;

    /**
     * Users repository instance
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * PipelinePermissionUsersInteractor constructor.
     * @param PipelinePermissionUsersOutputPort $output
     * @param PipelinePermissionRepositoryInterface $repository
     * @param PipelineRepositoryInterface $pipelineRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        PipelinePermissionUsersOutputPort $output,
        PipelinePermissionRepositoryInterface $repository,
        PipelineRepositoryInterface $pipelineRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->output = $output;
        $this->repository = $repository;
        $this->pipelineRepository = $pipelineRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritDoc
     */
    public function users(PipelinePermissionUsersRequestModel $requestModel): ViewModel
    {
        try {
            $permissions = $this->repository->all();
            $pipelines = [];

            foreach ($this->pipelineRepository->all(['uuid', 'name']) as $pipeline) {
                $pipelines[$pipeline->getUuid()] = $pipeline->getName();
            }

            $users = [];

            foreach ($permissions as $permission) {
                $userIdentifier = $permission->getUserID();
                if (!array_key_exists($userIdentifier, $users)) {
                    $user = Arr::except($this->userRepository->find($userIdentifier)->toArray(), [
                        'created_at',
                        'updated_at',
                        'guid',
                        'domain',
                    ]);
                    $user['permissions'] = [];

                    $users[$userIdentifier] = $user;
                }

                $pipelineName = $pipelines[$permission->getPipelineUuid()];

                $users[$userIdentifier]['permissions'][] = [
                    'uuid'      =>  $permission->getPipelineUuid(),
                    'name'      =>  $pipelineName,
                ];
            }

            return $this->output->users(new PipelinePermissionUsersResponseModel(
                array_values($users)
            ));
            // @codeCoverageIgnoreStart
        } catch (Throwable $throwable) {
            return $this->output->internalServerError(new PipelinePermissionUsersResponseModel(), $throwable);
        }
        // @codeCoverageIgnoreEnd
    }
}
