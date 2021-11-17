<?php

namespace ConsulConfigManager\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionInterface;

/**
 * Class PipelineExecution
 * @package ConsulConfigManager\Tasks\Models
 */
class PipelineExecution extends Model implements PipelineExecutionInterface
{
    /**
     * @inheritDoc
     */
    public $table = 'pipeline_executions';

    /**
     * @inheritDoc
     */
    protected $fillable = [];

    /**
     * @inheritDoc
     */
    protected $casts = [];

    /**
     * @inheritDoc
     */
    protected $guarded = [];

    /**
     * @inheritDoc
     */
    protected $with = [];

    /**
     * @inheritDoc
     */
    protected $dates = [];
}
