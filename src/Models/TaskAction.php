<?php

namespace ConsulConfigManager\Tasks\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ConsulConfigManager\Tasks\Factories\TaskActionFactory;
use ConsulConfigManager\Tasks\Interfaces\TaskActionInterface;

/**
 * Class TaskAction
 * @package ConsulConfigManager\Tasks\Models
 */
class TaskAction extends Pivot implements TaskActionInterface
{
    use HasFactory;

    /**
     * @inheritDoc
     */
    public $table = 'task_actions';

    /**
     * @inheritDoc
     * @var bool
     */
    public $timestamps = false;

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'action_uuid',
        'task_uuid',
        'order',
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        'action_uuid'       =>  'string',
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
        return TaskActionFactory::new();
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
    public function setTaskUuid(string $uuid): TaskActionInterface
    {
        $this->attributes['task_uuid'] = (string) $uuid;
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
    public function setActionUuid(string $uuid): TaskActionInterface
    {
        $this->attributes['action_uuid'] = (string) $uuid;
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
    public function setOrder(int $order): TaskActionInterface
    {
        $this->attributes['order'] = (int) $order;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function action(): HasOne
    {
        return $this->hasOne(Action::class, 'uuid', 'action_uuid');
    }

    /**
     * @inheritDoc
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_uuid', 'uuid');
    }
}
