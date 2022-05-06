<?php

namespace ConsulConfigManager\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use ConsulConfigManager\Consul\Agent\Models\Service;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ConsulConfigManager\Tasks\Factories\ActionHostFactory;
use ConsulConfigManager\Tasks\Interfaces\ActionHostInterface;

/**
 * Class ActionHost
 * @package ConsulConfigManager\Tasks\Models
 */
class ActionHost extends Model implements ActionHostInterface
{
    use HasFactory;

    /**
     * @inheritDoc
     */
    public $table = 'action_hosts';

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
        'service_uuid',
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        'action_uuid'       =>  'string',
        'service_uuid'      =>  'string',
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
        return ActionHostFactory::new();
    }

    /**
     * @return int
     */
    public function getID(): int
    {
        return (int) $this->attributes['id'];
    }

    /**
     * @param int $identifier
     * @return ActionHostInterface
     */
    public function setID(int $identifier): ActionHostInterface
    {
        $this->attributes['id'] = (int) $identifier;
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
    public function setActionUuid(string $action): ActionHostInterface
    {
        $this->attributes['action_uuid'] = (string) $action;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getServiceUuid(): string
    {
        return (string) $this->attributes['service_uuid'];
    }

    /**
     * @inheritDoc
     */
    public function setServiceUuid(string $id): ActionHostInterface
    {
        $this->attributes['service_uuid'] = (string) $id;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class, 'action_uuid', 'uuid');
    }

    /**
     * @inheritDoc
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_uuid', 'uuid');
    }
}
