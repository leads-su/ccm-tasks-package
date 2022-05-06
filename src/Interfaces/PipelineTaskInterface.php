<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interface PipelineTaskInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface PipelineTaskInterface extends SourcedInterface
{
    /**
     * Get instance by UUID
     * @param string $uuid
     * @param bool $withTrashed
     * @return PipelineTaskInterface|null
     */
    public static function uuid(string $uuid, bool $withTrashed = false): ?PipelineTaskInterface;

    /**
     * Get pipeline task uuid
     * @return string
     */
    public function getUuid(): string;

    /**
     * Set pipeline task uuid
     * @param string $uuid
     * @return $this
     */
    public function setUuid(string $uuid): self;

    /**
     * Get pipeline uuid
     * @return string
     */
    public function getPipelineUuid(): string;

    /**
     * Set pipeline uuid
     * @param string $uuid
     * @return PipelineTaskInterface
     */
    public function setPipelineUuid(string $uuid): PipelineTaskInterface;

    /**
     * Get task uuid
     * @return string
     */
    public function getTaskUuid(): string;

    /**
     * Set task uuid
     * @param string $uuid
     * @return PipelineTaskInterface
     */
    public function setTaskUuid(string $uuid): PipelineTaskInterface;

    /**
     * Get order
     * @return int
     */
    public function getOrder(): int;

    /**
     * Set order
     * @param int $order
     * @return PipelineTaskInterface
     */
    public function setOrder(int $order): PipelineTaskInterface;

    /**
     * Get pipeline reference model
     * @return BelongsTo
     */
    public function pipeline(): BelongsTo;

    /**
     * Get task reference model
     * @return BelongsTo
     */
    public function task(): BelongsTo;
}
