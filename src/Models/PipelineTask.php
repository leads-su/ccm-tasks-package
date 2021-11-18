<?php

namespace ConsulConfigManager\Tasks\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ConsulConfigManager\Tasks\Factories\PipelineTaskFactory;
use ConsulConfigManager\Tasks\Interfaces\PipelineTaskInterface;

/**
 * Class PipelineTask
 * @package ConsulConfigManager\Tasks\Models
 */
class PipelineTask extends Pivot implements PipelineTaskInterface
{
    use HasFactory;

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
        'order',
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        'pipeline_uuid'     =>  'string',
        'task_uuid'         =>  'string',
        'order'             =>  'integer',
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
    protected static function newFactory(): Factory
    {
        return PipelineTaskFactory::new();
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
    public function setPipelineUuid(string $uuid): PipelineTaskInterface
    {
        $this->attributes['pipeline_uuid'] = (string) $uuid;
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
    public function setTaskUuid(string $uuid): PipelineTaskInterface
    {
        $this->attributes['task_uuid'] = (string) $uuid;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getOrder(): int
    {
        return (int) $this->attributes['order'];
    }

    /**
     * @inheritDoc
     */
    public function setOrder(int $order): PipelineTaskInterface
    {
        $this->attributes['order'] = (int) $order;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class, 'pipeline_uuid', 'uuid');
    }

    /**
     * @inheritDoc
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_uuid', 'uuid');
    }
}
