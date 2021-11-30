<?php

namespace ConsulConfigManager\Tasks\UseCases\Pipeline\Delete;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface PipelineDeleteInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Pipeline\Delete
 */
interface PipelineDeleteInputPort
{
    /**
     * Delete information for specified entity
     * @param PipelineDeleteRequestModel $requestModel
     * @return ViewModel
     */
    public function delete(PipelineDeleteRequestModel $requestModel): ViewModel;
}
