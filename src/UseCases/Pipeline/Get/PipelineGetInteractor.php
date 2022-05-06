<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Get;

use Throwable;
use Illuminate\Support\Arr;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Interfaces\PipelineRepositoryInterface;

/**
 * Class PipelineGetInteractor
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Get
 */
class PipelineGetInteractor implements PipelineGetInputPort
{
    /**
     * Output port instance
     * @var PipelineGetOutputPort
     */
    private PipelineGetOutputPort $output;

    /**
     * Repository instance
     * @var PipelineRepositoryInterface
     */
    private PipelineRepositoryInterface $repository;

    /**
     * PipelineGetInteractor constructor.
     * @param PipelineGetOutputPort $output
     * @param PipelineRepositoryInterface $repository
     * @return void
     */
    public function __construct(PipelineGetOutputPort $output, PipelineRepositoryInterface $repository)
    {
        $this->output = $output;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function get(PipelineGetRequestModel $requestModel): ViewModel
    {
        try {
            $pipeline = $this->repository->findByManyOrFail(
                fields: ['id', 'uuid'],
                value: $requestModel->getIdentifier(),
                append: ['history', 'tasks_list_extended'],
                withDeleted: $requestModel->getRequest()->get('with_deleted', false)
            )->toArray();

            if (isset($pipeline['tasks_list'])) {
                $pipeline['tasks'] = Arr::get($pipeline, 'tasks_list');
                unset($pipeline['tasks_list']);
            }
            if (isset($pipeline['tasks_list_extended'])) {
                $pipeline['tasks'] = Arr::get($pipeline, 'tasks_list_extended');
                unset($pipeline['tasks_list_extended']);
            }

            return $this->output->get(new PipelineGetResponseModel($pipeline));
        } catch (Throwable $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->output->notFound(new PipelineGetResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->internalServerError(new PipelineGetResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
