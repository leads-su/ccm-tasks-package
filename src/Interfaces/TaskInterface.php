<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Interface TaskInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface TaskInterface extends SourcedInterface, Arrayable
{
    /**
     * Get task instance by UUID
     * @param string $uuid
     * @param bool $withTrashed
     * @return TaskInterface|null
     */
    public static function uuid(string $uuid, bool $withTrashed = false): ?TaskInterface;

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
    public function setID(int $id): self;

    /**
     * Get task uuid
     * @return string
     */
    public function getUuid(): string;

    /**
     * Set task uuid
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
    public function setName(string $name): self;

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
    public function setDescription(string $description): self;

    /**
     * Check whether task should fail on error
     * @return bool
     */
    public function isFailingOnError(): bool;

    /**
     * Set whether task should fail on error
     * @param bool $failOnError
     * @return $this
     */
    public function failOnError(bool $failOnError): self;

    /**
     * Get list of actions for given task
     * @return HasManyThrough
     */
    public function actions(): HasManyThrough;

    /**
     * Get list of actions references
     * @return HasMany
     */
    public function actionsList(): HasMany;

    /**
     * Get list of actions bound to this task as array
     * @return array
     */
    public function getActionsListAttribute(): array;

    /**
     * Get list of actions bound to this task as array (with extended information)
     * @return array
     */
    public function getActionsListExtendedAttribute(): array;
}
