<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Create;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface ActionCreateInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Action\Create
 */
interface ActionCreateInputPort
{
    /**
     * Create action
     * @param ActionCreateRequestModel $requestModel
     * @return ViewModel
     */
    public function create(ActionCreateRequestModel $requestModel): ViewModel;
}
