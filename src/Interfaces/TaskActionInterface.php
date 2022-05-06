<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interface TaskActionInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface TaskActionInterface extends SourcedInterface
{
    /**
     * Get instance by UUID
     * @param string $uuid
     * @param bool $withTrashed
     * @return TaskActionInterface|null
     */
    public static function uuid(string $uuid, bool $withTrashed = false): ?TaskActionInterface;

    /**
     * Get task action uuid
     * @return string
     */
    public function getUuid(): string;

    /**
     * Set task action uuid
     * @param string $uuid
     * @return $this
     */
    public function setUuid(string $uuid): self;

    /**
     * Get task uuid
     * @return string
     */
    public function getTaskUuid(): string;

    /**
     * Set task uuid
     * @param string $uuid
     * @return TaskActionInterface
     */
    public function setTaskUuid(string $uuid): TaskActionInterface;

    /**
     * Get action uuid
     * @return string
     */
    public function getActionUuid(): string;

    /**
     * Set action uuid
     * @param string $uuid
     * @return TaskActionInterface
     */
    public function setActionUuid(string $uuid): TaskActionInterface;

    /**
     * Get order
     * @return int
     */
    public function getOrder(): int;

    /**
     * Set order
     * @param int $order
     * @return TaskActionInterface
     */
    public function setOrder(int $order): TaskActionInterface;

    /**
     * Get action instance
     * @return HasOne
     */
    public function action(): HasOne;

    /**
     * Get task instance
     * @return BelongsTo
     */
    public function task(): BelongsTo;
}
