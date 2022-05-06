<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use ArrayAccess;
use JsonSerializable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interface PipelinePermissionInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface PipelinePermissionInterface extends Arrayable, ArrayAccess, Jsonable, JsonSerializable
{
    /**
     * Get user identifier
     * @return int
     */
    public function getUserID(): int;

    /**
     * Set user identifier
     * @param int $identifier
     * @return $this
     */
    public function setUserID(int $identifier): self;

    /**
     * Get pipeline identifier
     * @return string
     */
    public function getPipelineUuid(): string;

    /**
     * Set pipeline identifier
     * @param string $identifier
     * @return $this
     */
    public function setPipelineUuid(string $identifier): self;

    /**
     * Relation to retrieve user to whom this permission belongs to
     * @return BelongsTo
     */
    public function user(): BelongsTo;

    /**
     * Relation to retrieve pipeline which assigned to this permission
     * @return HasOne
     */
    public function pipeline(): HasOne;
}
