<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interface TaskActionInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface TaskActionInterface
{
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
