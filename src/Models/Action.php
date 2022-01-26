<?php

namespace ConsulConfigManager\Tasks\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ConsulConfigManager\Consul\Agent\Models\Service;
use ConsulConfigManager\Tasks\Factories\ActionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use ConsulConfigManager\Consul\Agent\Interfaces\ServiceInterface;

/**
 * Class Action
 * @package ConsulConfigManager\Tasks\Models
 *
 * @property \Illuminate\Database\Eloquent\Collection hosts
 */
class Action extends AbstractSourcedModel implements ActionInterface
{
    use SoftDeletes;
    use HasFactory;

    /**
     * @inheritDoc
     */
    public $table = 'actions';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'id',
        'uuid',
        'name',
        'description',
        'type',
        'command',
        'arguments',
        'working_dir',
        'run_as',
        'use_sudo',
        'fail_on_error',
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        'id'                    =>  'integer',
        'uuid'                  =>  'string',
        'name'                  =>  'string',
        'description'           =>  'string',
        'type'                  =>  'integer',
        'command'               =>  'string',
        'arguments'             =>  'array',
        'working_dir'           =>  'string',
        'run_as'                =>  'string',
        'use_sudo'              =>  'boolean',
        'fail_on_error'         =>  'boolean',
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
    public static function uuid(string $uuid, bool $withTrashed = false): ?ActionInterface
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
        return ActionFactory::new();
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
    public function setID(int $id): ActionInterface
    {
        $this->attributes['id'] = (int) $id;
        return $this;
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
    public function setUuid(string $uuid): ActionInterface
    {
        $this->attributes['uuid'] = (string) $uuid;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return (string) $this->attributes['name'];
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): ActionInterface
    {
        $this->attributes['name'] = (string) $name;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return (string) $this->attributes['description'];
    }

    /**
     * @inheritDoc
     */
    public function setDescription(string $description): ActionInterface
    {
        $this->attributes['description'] = (string) $description;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getType(): int
    {
        return (int) $this->attributes['type'];
    }

    /**
     * @inheritDoc
     */
    public function setType(int $type): ActionInterface
    {
        $this->attributes['type'] = (int) $type;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getCommand(): string
    {
        return $this->attributes['command'];
    }

    /**
     * @inheritDoc
     */
    public function setCommand(string $command): ActionInterface
    {
        $this->attributes['command'] = (string) $command;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getArguments(): array
    {
        return json_decode($this->attributes['arguments'], true);
    }

    /**
     * @inheritDoc
     */
    public function setArguments(array $arguments): ActionInterface
    {
        $this->attributes['arguments'] = (string) json_encode($arguments);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getWorkingDirectory(): ?string
    {
        return $this->attributes['working_dir'];
    }

    /**
     * @inheritDoc
     */
    public function setWorkingDirectory(?string $workingDirectory = null): ActionInterface
    {
        $this->attributes['working_dir'] = $workingDirectory;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRunAs(): ?string
    {
        return $this->attributes['run_as'];
    }

    /**
     * @inheritDoc
     */
    public function setRunAs(?string $runAs = null): ActionInterface
    {
        $this->attributes['run_as'] = $runAs;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isUsingSudo(): bool
    {
        return (bool) $this->attributes['use_sudo'];
    }

    /**
     * @inheritDoc
     */
    public function useSudo(bool $useSudo): ActionInterface
    {
        $this->attributes['use_sudo'] = (bool) $useSudo;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isFailingOnError(): bool
    {
        return (bool) $this->attributes['fail_on_error'];
    }

    /**
     * @inheritDoc
     */
    public function failOnError(bool $failOnError): ActionInterface
    {
        $this->attributes['fail_on_error'] = (bool) $failOnError;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function hosts(): HasManyThrough
    {
        return $this->hasManyThrough(
            Service::class,
            ActionHost::class,
            'action_uuid',
            'uuid',
            'uuid',
            'service_uuid'
        );
    }

    /**
     * @inheritDoc
     */
    public function servers(): HasMany
    {
        return $this->hasMany(
            ActionHost::class,
            'action_uuid',
            'uuid'
        );
    }

    /**
     * @inheritDoc
     */
    public function getServersAttribute(): array
    {
        return $this->servers()->getResults()->map(function (ActionHost $actionHost): string {
            return $actionHost->getServiceUuid();
        })->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getServersExtendedAttribute(): array
    {
        return $this->hosts()->getResults()->map(function (ServiceInterface $service): array {
            return [
                'uuid'          =>  $service->getUuid(),
                'identifier'    =>  $service->getIdentifier(),
                'service'       =>  $service->getService(),
                'address'       =>  $service->getAddress(),
                'port'          =>  $service->getPort(),
                'environment'   =>  $service->getEnvironment(),
            ];
        })->toArray();
    }
}
