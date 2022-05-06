<?php

namespace ConsulConfigManager\Tasks\UseCases\Action\Update;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface ActionUpdateInputPort
 * @package ConsulConfigManager\Tasks\UseCases\Action\Update
 */
interface ActionUpdateInputPort
{
    /**
     * Update action
     * @param ActionUpdateRequestModel $requestModel
     * @return ViewModel
     */
    public function update(ActionUpdateRequestModel $requestModel): ViewModel;
}
