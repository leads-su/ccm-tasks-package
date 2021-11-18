<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interface PipelineTaskInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface PipelineTaskInterface
{
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
