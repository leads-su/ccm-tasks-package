<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Interface PipelineExecutionInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface PipelineExecutionInterface
{
    /**
     * Get pipeline execution instance by UUID
     * @param string $uuid
     * @param bool $withTrashed
     * @return PipelineExecutionInterface|null
     */
    public static function uuid(string $uuid, bool $withTrashed = false): ?PipelineExecutionInterface;

    /**
     * Get execution id
     * @return int
     */
    public function getID(): int;

    /**
     * Set execution id
     * @param int $id
     * @return PipelineExecutionInterface
     */
    public function setID(int $id): PipelineExecutionInterface;

    /**
     * Get execution uuid
     * @return string
     */
    public function getUuid(): string;

    /**
     * Set execution uuid
     * @param string $uuid
     * @return PipelineExecutionInterface
     */
    public function setUuid(string $uuid): PipelineExecutionInterface;

    /**
     * Get pipeline uuid
     * @return string
     */
    public function getPipelineUuid(): string;

    /**
     * Set pipeline uuid
     * @param string $uuid
     * @return PipelineExecutionInterface
     */
    public function setPipelineUuid(string $uuid): PipelineExecutionInterface;

    /**
     * Get execution state
     * @return int
     */
    public function getState(): int;

    /**
     * Set execution state
     * @param int $state
     * @return PipelineExecutionInterface
     */
    public function setState(int $state): PipelineExecutionInterface;

    /**
     * Get instance of pipeline model
     * @return HasOne
     */
    public function pipeline(): HasOne;
}
