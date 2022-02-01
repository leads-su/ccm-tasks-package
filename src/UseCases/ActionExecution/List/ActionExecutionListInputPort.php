<?php

namespace ConsulConfigManager\Tasks\UseCases\ActionExecution\List;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface ActionExecutionListInputPort
 * @package ConsulConfigManager\Tasks\UseCases\ActionExecution\List
 */
interface ActionExecutionListInputPort {

    /**
     * Input port for "list"
     * @param ActionExecutionListRequestModel $requestModel
     * @return ViewModel
     */
    public function list(ActionExecutionListRequestModel $requestModel): ViewModel;


}
