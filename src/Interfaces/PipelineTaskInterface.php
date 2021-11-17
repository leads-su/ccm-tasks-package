<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Interface PipelineTaskInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface PipelineTaskInterface
{
    /**
     * Get pipeline reference model
     * @return HasOne
     */
    public function pipeline(): HasOne;

    /**
     * Get task reference model
     * @return HasOne
     */
    public function task(): HasOne;
}
