<?php

namespace ConsulConfigManager\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use ConsulConfigManager\Consul\Agent\Models\Service;
use ConsulConfigManager\Tasks\Interfaces\ActionHostInterface;

/**
 * Class ActionHost
 * @package ConsulConfigManager\Tasks\Models
 */
class ActionHost extends Model implements ActionHostInterface
{
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
        'service_id',
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        'action_uuid'       =>  'string',
        'service_id'        =>  'integer',
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
    public function getServiceId(): int
    {
        return (int) $this->attributes['service_id'];
    }

    /**
     * @inheritDoc
     */
    public function setServiceId(int $id): ActionHostInterface
    {
        $this->attributes['service_id'] = (int) $id;
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
    public function service(): HasOne
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }
}
