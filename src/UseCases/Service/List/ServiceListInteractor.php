<?php

namespace ConsulConfigManager\Tasks\UseCases\Service\List;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Consul\Agent\Interfaces\ServiceRepositoryInterface;

/**
 * Class ServiceListInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Service\List
 */
class ServiceListInteractor implements ServiceListInputPort
{
    /**
     * Output port instance
     * @var ServiceListOutputPort
     */
    private ServiceListOutputPort $output;

    /**
     * Repository instance
     * @var ServiceRepositoryInterface
     */
    private ServiceRepositoryInterface $repository;

    /**
     * ServiceListInteractor constructor.
     * @param ServiceListOutputPort $output
     * @param ServiceRepositoryInterface $repository
     * @return void
     */
    public function __construct(ServiceListOutputPort $output, ServiceRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function list(ServiceListRequestModel $requestModel): ViewModel
    {
        try {
            $services = $this->repository->all([
                'uuid',
                'service',
                'identifier',
                'address',
                'port',
                'environment',
            ]);
            return $this->output->list(new ServiceListResponseModel($services));
            // @codeCoverageIgnoreStart
        } catch (Throwable $exception) {
            return $this->output->internalServerError(new ServiceListResponseModel(), $exception);
        }
        // @codeCoverageIgnoreEnd
    }
}
