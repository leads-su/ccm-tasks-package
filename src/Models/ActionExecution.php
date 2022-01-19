<?php

namespace ConsulConfigManager\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ConsulConfigManager\Tasks\Factories\ActionExecutionFactory;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionInterface;

/**
 * Class ActionExecution
 * @package ConsulConfigManager\Tasks\Models
 *
 * @property Pipeline pipeline
 * @property ActionExecutionLog log
 * @property PipelineExecution pipelineExecution
 *
 */
class ActionExecution extends Model implements ActionExecutionInterface
{
    use HasFactory;

    /**
     * @inheritDoc
     */
    public $table = 'action_executions';

    /**
     * @inheritDoc
     */
    public $fillable = [
        'server_uuid',
        'action_uuid',
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
        'server_uuid'               =>  'string',
        'action_uuid'               =>  'string',
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
        return ActionExecutionFactory::new();
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
    public function setID(int $id): ActionExecutionInterface
    {
        $this->attributes['id'] = (int) $id;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getServerUuid(): string|null
    {
        return $this->attributes['server_uuid'];
    }

    /**
     * @inheritDoc
     */
    public function setServerUuid(string $uuid): ActionExecutionInterface
    {
        $this->attributes['server_uuid'] = (string) $uuid;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getActionUuid(): string
    {
        return (string) $this->attributes['action_uuid'];
    }

    /**
     * @inheritDoc
     */
    public function setActionUuid(string $uuid): ActionExecutionInterface
    {
        $this->attributes['action_uuid'] = (string) $uuid;
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
    public function setTaskUuid(string $uuid): ActionExecutionInterface
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
    public function setPipelineUuid(string $uuid): ActionExecutionInterface
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
    public function setPipelineExecutionUuid(string $uuid): ActionExecutionInterface
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
    public function setState(int $state): ActionExecutionInterface
    {
        $this->attributes['state'] = (int) $state;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function action(): BelongsTo
    {
        return $this->belongsTo(
            Action::class,
            'action_uuid',
            'uuid',
        );
    }

    /**
     * @inheritDoc
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(
            Task::class,
            'task_uuid',
            'uuid',
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
            'uuid',
        );
    }

    /**
     * @inheritDoc
     */
    public function log(): HasOne
    {
        return $this->hasOne(
            ActionExecutionLog::class,
            'action_execution_id',
            'id'
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
