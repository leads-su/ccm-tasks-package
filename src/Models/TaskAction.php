<?php

namespace ConsulConfigManager\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ConsulConfigManager\Tasks\Interfaces\TaskActionInterface;

/**
 * Class TaskAction
 * @package ConsulConfigManager\Tasks\Models
 */
class TaskAction extends Model implements TaskActionInterface
{
    /**
     * @inheritDoc
     */
    public $table = 'tasks_actions';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'action_uuid',
        'task_uuid',
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        'action_uuid'       =>  'string',
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
