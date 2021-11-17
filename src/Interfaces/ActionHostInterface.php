<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Interface ActionHostInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface ActionHostInterface
{
    /**
     * Get action uuid
     * @return string
     */
    public function getActionUuid(): string;

    /**
     * Set action uuid
     * @param string $action
     * @return ActionHostInterface
     */
    public function setActionUuid(string $action): ActionHostInterface;

    /**
     * Get service id
     * @return int
     */
    public function getServiceId(): int;

    /**
     * Set service id
     * @param int $id
     * @return ActionHostInterface
     */
    public function setServiceId(int $id): ActionHostInterface;

    /**
     * Get action reference model
     * @return HasOne
     */
    public function action(): HasOne;

    /**
     * Get service reference model
     * @return HasOne
     */
    public function service(): HasOne;
}
