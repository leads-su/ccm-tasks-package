<?php

namespace ConsulConfigManager\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ConsulConfigManager\Tasks\Factories\PipelineExecutionFactory;
use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionInterface;

/**
 * Class PipelineExecution
 * @package ConsulConfigManager\Tasks\Models
 */
class PipelineExecution extends Model implements PipelineExecutionInterface
{
    use HasFactory;

    /**
     * @inheritDoc
     */
    public $table = 'pipeline_executions';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'uuid',
        'pipeline_uuid',
        'task_uuid',
        'state',
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        'id'                =>  'integer',
        'uuid'              =>  'string',
        'pipeline_uuid'     =>  'string',
        'task_uuid'         =>  'string',
        'state'             =>  'integer',
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
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @inheritDoc
     */
    protected static function newFactory(): Factory
    {
        return PipelineExecutionFactory::new();
    }

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
