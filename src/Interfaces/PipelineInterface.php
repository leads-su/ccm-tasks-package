<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Interface PipelineInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface PipelineInterface extends SourcedInterface, Arrayable
{
    /**
     * Get task instance by UUID
     * @param string $uuid
     * @param bool $withTrashed
     * @return PipelineInterface|null
     */
    public static function uuid(string $uuid, bool $withTrashed = false): ?PipelineInterface;

    /**
     * Get task identifier
     * @return int
     */
    public function getID(): int;

    /**
     * Set task identifier
     * @param int $id
     * @return $this
     */
    public function setID(int $id): PipelineInterface;

    /**
     * Get pipeline uuid
     * @return string
     */
    public function getUuid(): string;

    /**
     * Set pipeline uuid
     * @param string $uuid
     * @return $this
     */
    public function setUuid(string $uuid): self;

    /**
     * Get task name
     * @return string
     */
    public function getName(): string;

    /**
     * Set task name
     * @param string $name
     * @return $this
     */
    public function setName(string $name): PipelineInterface;

    /**
     * Get task description
     * @return string
     */
    public function getDescription(): string;

    /**
     * Set task description
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): PipelineInterface;

    /**
     * Get list of tasks for pipeline
     * @return HasManyThrough
     */
    public function tasks(): HasManyThrough;

    /**
     * Get list of tasks references
     * @return HasMany
     */
    public function tasksList(): HasMany;

    /**
     * Get list of tasks for this pipeline as array
     * @return array
     */
    public function getTasksListAttribute(): array;

    /**
     * Get list of tasks for this pipeline as array (with extended information)
     * @return array
     */
    public function getTasksListExtendedAttribute(): array;
}
