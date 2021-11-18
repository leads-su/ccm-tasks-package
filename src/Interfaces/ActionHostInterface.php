<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * @return string
     */
    public function getServiceUuid(): string;

    /**
     * Set service id
     * @param string $id
     * @return ActionHostInterface
     */
    public function setServiceUuid(string $id): ActionHostInterface;

    /**
     * Get action reference model
     * @return BelongsTo
     */
    public function action(): BelongsTo;

    /**
     * Get service reference model
     * @return BelongsTo
     */
    public function service(): BelongsTo;
}
