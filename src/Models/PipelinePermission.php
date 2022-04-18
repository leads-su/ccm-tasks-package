<?php

namespace ConsulConfigManager\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use ConsulConfigManager\Users\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ConsulConfigManager\Tasks\Interfaces\PipelinePermissionInterface;

/**
 * Class PipelinePermission
 * @package ConsulConfigManager\Tasks\Models
 */
class PipelinePermission extends Model implements PipelinePermissionInterface
{
    /**
     * @inheritDoc
     */
    public $table = 'pipeline_permissions';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'user_id',
        'pipeline_uuid',
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        'id'                =>  'integer',
        'user_id'           =>  'integer',
        'pipeline_uuid'     =>  'string',
    ];

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
    public function getUserID(): int
    {
        return (int) $this->attributes['user_id'];
    }

    /**
     * @inheritDoc
     */
    public function setUserID(int $identifier): self
    {
        $this->attributes['user_id'] = (int) $identifier;
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
    public function setPipelineUuid(string $identifier): self
    {
        $this->attributes['pipeline_uuid'] = (string) $identifier;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @inheritDoc
     */
    public function pipeline(): HasOne
    {
        return $this->hasOne(Pipeline::class, 'uuid', 'pipeline_uuid');
    }
}
