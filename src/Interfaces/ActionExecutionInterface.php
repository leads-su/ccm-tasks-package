<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interface ActionExecutionInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface ActionExecutionInterface
{
    /**
     * Get action execution id
     * @return int
     */
    public function getID(): int;

    /**
     * Set action execution id
     * @param int $id
     * @return ActionExecutionInterface
     */
    public function setID(int $id): ActionExecutionInterface;

    /**
     * Get action uuid
     * @return string
     */
    public function getActionUuid(): string;

    /**
     * Set action uuid
     * @param string $uuid
     * @return ActionExecutionInterface
     */
    public function setActionUuid(string $uuid): ActionExecutionInterface;

    /**
     * Get task uuid
     * @return string
     */
    public function getTaskUuid(): string;

    /**
     * Set task uuid
     * @param string $uuid
     * @return ActionExecutionInterface
     */
    public function setTaskUuid(string $uuid): ActionExecutionInterface;

    /**
     * Get pipeline uuid
     * @return string
     */
    public function getPipelineUuid(): string;

    /**
     * Set pipeline uuid
     * @param string $uuid
     * @return ActionExecutionInterface
     */
    public function setPipelineUuid(string $uuid): ActionExecutionInterface;

    /**
     * Get execution state
     * @return int
     */
    public function getState(): int;

    /**
     * Set execution state
     * @param int $state
     * @return ActionExecutionInterface
     */
    public function setState(int $state): ActionExecutionInterface;

    /**
     * Get instance of action model
     * @return BelongsTo
     */
    public function action(): BelongsTo;

    /**
     * Get instance of task model
     * @return BelongsTo
     */
    public function task(): BelongsTo;

    /**
     * Get instance of pipeline model
     * @return BelongsTo
     */
    public function pipeline(): BelongsTo;
}
