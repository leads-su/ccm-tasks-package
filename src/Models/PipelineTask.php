<?php

namespace ConsulConfigManager\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskInterface;

/**
 * Class PipelineTask
 * @package ConsulConfigManager\Tasks\Models
 */
class PipelineTask extends Model implements PipelineTaskInterface
{
    /**
     * @inheritDoc
     */
    public $table = 'pipeline_tasks';

    /**
     * @inheritDoc
     * @var bool
     */
    public $timestamps = false;

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'pipeline_uuid',
        'task_uuid',
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        'pipeline_uuid'     =>  'string',
        'task_uuid'         =>  'string',
    ];

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

    /**
     * @inheritDoc
     */
    public function pipeline(): HasOne
    {
        return $this->hasOne(Pipeline::class, 'uuid', 'pipeline_uuid');
    }

    /**
     * @inheritDoc
     */
    public function task(): HasOne
    {
        return $this->hasOne(Task::class, 'uuid', 'task_uuid');
    }
}
