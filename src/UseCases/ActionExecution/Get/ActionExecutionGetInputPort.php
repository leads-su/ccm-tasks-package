<?php

namespace ConsulConfigManager\Tasks\UseCases\ActionExecution\Get;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface ActionExecutionGetInputPort
 * @package ConsulConfigManager\Tasks\UseCases\ActionExecution\Get
 */
interface ActionExecutionGetInputPort {

    /**
     * Input port for "get"
     * @param ActionExecutionGetRequestModel $requestModel
     * @return ViewModel
     */
    public function get(ActionExecutionGetRequestModel $requestModel): ViewModel;

}
