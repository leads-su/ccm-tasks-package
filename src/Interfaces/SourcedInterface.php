<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Support\Collection;

/**
 * Interface SourcedInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface SourcedInterface
{
    /**
     * Retrieve model changes history
     * @param string|null $uuid
     * @return Collection
     */
    public function history(?string $uuid = null): Collection;

    /**
     * Retrieve model changes history as an attribute
     * @return Collection
     */
    public function getHistoryAttribute(): Collection;
}
