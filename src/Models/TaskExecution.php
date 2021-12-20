<?php

namespace ConsulConfigManager\Tasks\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ConsulConfigManager\Tasks\Factories\TaskExecutionFactory;
use ConsulConfigManager\Tasks\Interfaces\TaskExecutionInterface;

/**
 * Class TaskExecution
 * @package ConsulConfigManager\Tasks\Models
 */
class TaskExecution extends AbstractSourcedModel implements TaskExecutionInterface
{
    use HasFactory;

    /**
     * @inheritDoc
     */
    public $table = 'task_executions';

    /**
     * @inheritDoc
     */
    public $fillable = [
        'task_uuid',
        'pipeline_uuid',
        'pipeline_execution_uuid',
        'state',
    ];

    /**
     * @inheritDoc
     */
    public $casts = [
        'id'                        =>  'integer',
        'task_uuid'                 =>  'string',
        'pipeline_uuid'             =>  'string',
        'pipeline_execution_uuid'   =>  'string',
        'state'                     =>  'integer',
    ];

    /**
     * @inheritDoc
     */
    protected $guarded = [];

    /**
     * @inheritDoc
     */
    protected $hidden = [];

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
    protected $with = [];

    /**
     * @inheritDoc
     */
    protected static function newFactory(): Factory
    {
        return TaskExecutionFactory::new();
    }

    /**
     * @inheritDoc
     */
    public function getID(): int
    {
        return (int) $this->attributes['id'];
    }

    /**
     * @inheritDoc
     */
    public function setID(int $id): TaskExecutionInterface
    {
        $this->attributes['id'] = (int) $id;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTaskUuid(): string
    {
        return (string) $this->attributes['task_uuid'];
    }

    /**
     * @inheritDoc
     */
    public function setTaskUuid(string $uuid): TaskExecutionInterface
    {
        $this->attributes['task_uuid'] = (string) $uuid;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPipelineUuid(): string
    {
        return (string) $this->attributes['pipeline_uuid'];
    }

    /**
     * @inheritDoc
     */
    public function setPipelineUuid(string $uuid): TaskExecutionInterface
    {
        $this->attributes['pipeline_uuid'] = (string) $uuid;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPipelineExecutionUuid(): string
    {
        return (string) $this->attributes['pipeline_execution_uuid'];
    }

    /**
     * @inheritDoc
     */
    public function setPipelineExecutionUuid(string $uuid): TaskExecutionInterface
    {
        $this->attributes['pipeline_execution_uuid'] = (string) $uuid;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getState(): int
    {
        return (int) $this->attributes['state'];
    }

    /**
     * @inheritDoc
     */
    public function setState(int $state): TaskExecutionInterface
    {
        $this->attributes['state'] = (int) $state;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(
            Task::class,
            'task_uuid',
            'uuid'
        );
    }

    /**
     * @inheritDoc
     */
    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(
            Pipeline::class,
            'pipeline_uuid',
            'uuid'
        );
    }

    /**
     * @inheritDoc
     */
    public function pipelineExecution(): BelongsTo
    {
        return $this->belongsTo(
            PipelineExecution::class,
            'pipeline_execution_uuid',
            'uuid'
        );
    }
}
