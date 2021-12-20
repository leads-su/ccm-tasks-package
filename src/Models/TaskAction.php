<?php

namespace ConsulConfigManager\Tasks\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
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
class TaskAction extends AbstractSourcedPivot implements TaskActionInterface
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @inheritDoc
     */
    public $table = 'task_actions';

    /**
     * @inheritDoc
     */
    protected $primaryKey = 'uuid';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'uuid',
        'task_uuid',
        'action_uuid',
        'order',
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        'uuid'              =>  'string',
        'task_uuid'         =>  'string',
        'action_uuid'       =>  'string',
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
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @inheritDoc
     */
    public static function uuid(string $uuid, bool $withTrashed = false): ?TaskActionInterface
    {
        $query = static::where('uuid', '=', $uuid);
        if ($withTrashed) {
            return $query->withTrashed()->first();
        }
        return $query->first();
    }

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
    public function getUuid(): string
    {
        return (string) $this->attributes['uuid'];
    }

    /**
     * @inheritDoc
     */
    public function setUuid(string $uuid): TaskActionInterface
    {
        $this->attributes['uuid'] = (string) $uuid;
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
