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
     * Get instance of pipeline model
     * @return HasOne
     */
    public function pipeline(): HasOne;


    /**
     * Get instance of task model
     * @return HasOne
     */
    public function task(): HasOne;
}
