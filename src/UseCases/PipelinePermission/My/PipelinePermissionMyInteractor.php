<?php

namespace ConsulConfigManager\Tasks\UseCases\PipelinePermission\My;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Tasks\Interfaces\PipelinePermissionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelinePermissionRepositoryInterface;

/**
 * Class PipelinePermissionMyInteractor
 * @package ConsulConfigManager\Tasks\UseCases\PipelinePermission\My
 */
class PipelinePermissionMyInteractor implements PipelinePermissionMyInputPort
{
    /**
     * Output port instance
     * @var PipelinePermissionMyOutputPort
     */
    private PipelinePermissionMyOutputPort $output;

    /**
     * Repository instance
     * @var PipelinePermissionRepositoryInterface
     */
    private PipelinePermissionRepositoryInterface $repository;

    /**
     * PipelinePermissionMyInteractor constructor.
     * @param PipelinePermissionMyOutputPort $output
     * @param PipelinePermissionRepositoryInterface $repository
     * @return void
     */
    public function __construct(PipelinePermissionMyOutputPort $output, PipelinePermissionRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function my(PipelinePermissionMyRequestModel $requestModel): ViewModel
    {
        try {
            $entities = [];

            if (($user = request()->user()) !== null) {
                $entities = $this->repository->findManyBy('user_id', $user->getID(), ['pipeline_uuid']);
            }

            return $this->output->my(new PipelinePermissionMyResponseModel(
                $entities->map(function (PipelinePermissionInterface $entity): string {
                    return $entity->getPipelineUuid();
                })
            ));
            // @codeCoverageIgnoreStart
        } catch (Throwable $throwable) {
            return $this->output->internalServerError(new PipelinePermissionMyResponseModel(), $throwable);
        }
        // @codeCoverageIgnoreEnd
    }
}
