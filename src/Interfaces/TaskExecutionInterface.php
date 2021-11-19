<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interface TaskExecutionInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface TaskExecutionInterface
{
    /**
     * Get action execution id
     * @return int
     */
    public function getID(): int;

    /**
     * Set action execution id
     * @param int $id
     * @return TaskExecutionInterface
     */
    public function setID(int $id): TaskExecutionInterface;

    /**
     * Get task uuid
     * @return string
     */
    public function getTaskUuid(): string;

    /**
     * Set task uuid
     * @param string $uuid
     * @return TaskExecutionInterface
     */
    public function setTaskUuid(string $uuid): TaskExecutionInterface;

    /**
     * Get pipeline uuid
     * @return string
     */
    public function getPipelineUuid(): string;

    /**
     * Set pipeline uuid
     * @param string $uuid
     * @return TaskExecutionInterface
     */
    public function setPipelineUuid(string $uuid): TaskExecutionInterface;

    /**
     * Get pipeline execution uuid
     * @return string
     */
    public function getPipelineExecutionUuid(): string;

    /**
     * Set pipeline execution uuid
     * @param string $uuid
     * @return TaskExecutionInterface
     */
    public function setPipelineExecutionUuid(string $uuid): TaskExecutionInterface;

    /**
     * Get execution state
     * @return int
     */
    public function getState(): int;

    /**
     * Set execution state
     * @param int $state
     * @return TaskExecutionInterface
     */
    public function setState(int $state): TaskExecutionInterface;

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

    /**
     * Get instance of pipeline execution model
     * @return BelongsTo
     */
    public function pipelineExecution(): BelongsTo;
}
