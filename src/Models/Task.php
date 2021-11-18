<?php

namespace ConsulConfigManager\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\Factory;
use ConsulConfigManager\Tasks\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ConsulConfigManager\Tasks\Interfaces\TaskInterface;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Class Task
 * @package ConsulConfigManager\Tasks\Models
 */
class Task extends Model implements TaskInterface
{
    use SoftDeletes;
    use HasFactory;

    /**
     * @inheritDoc
     */
    public $table = 'tasks';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'uuid',
        'name',
        'description',
        'type',
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        'id'            =>  'integer',
        'uuid'          =>  'string',
        'name'          =>  'string',
        'description'   =>  'string',
        'type'          =>  'integer',
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
    public static function uuid(string $uuid, bool $withTrashed = false): ?TaskInterface
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
        return TaskFactory::new();
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
    public function setID(int $id): TaskInterface
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
    public function setUuid(string $uuid): TaskInterface
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
    public function setName(string $name): TaskInterface
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
    public function setDescription(string $description): TaskInterface
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
    public function setType(int $type): TaskInterface
    {
        $this->attributes['type'] = (int) $type;
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function actions(): HasManyThrough
    {
        return $this->hasManyThrough(
            Action::class,
            TaskAction::class,
            'task_uuid',
            'uuid',
            'uuid',
            'action_uuid'
        );
    }
}
