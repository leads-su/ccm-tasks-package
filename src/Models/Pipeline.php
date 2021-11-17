<?php

namespace ConsulConfigManager\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;

/**
 * Class Pipeline
 * @package ConsulConfigManager\Tasks\Models
 */
class Pipeline extends Model implements PipelineInterface
{
    /**
     * @inheritDoc
     */
    public $table = 'pipelines';

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
