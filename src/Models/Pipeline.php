<?php

namespace ConsulConfigManager\Tasks\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ConsulConfigManager\Tasks\Factories\PipelineFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use ConsulConfigManager\Tasks\Interfaces\PipelineInterface;

/**
 * Class Pipeline
 * @package ConsulConfigManager\Tasks\Models
 */
class Pipeline extends AbstractSourcedModel implements PipelineInterface
{
    use SoftDeletes;
    use HasFactory;

    /**
     * @inheritDoc
     */
    public $table = 'pipelines';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'uuid',
        'name',
        'description',
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        'id'            =>  'integer',
        'uuid'          =>  'string',
        'name'          =>  'string',
        'description'   =>  'string',
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
    public static function uuid(string $uuid, bool $withTrashed = false): ?PipelineInterface
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
        return PipelineFactory::new();
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
    public function setID(int $id): PipelineInterface
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
    public function setUuid(string $uuid): PipelineInterface
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
    public function setName(string $name): PipelineInterface
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
    public function setDescription(string $description): PipelineInterface
    {
        $this->attributes['description'] = (string) $description;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function tasks(): HasManyThrough
    {
        return $this->hasManyThrough(
            Task::class,
            PipelineTask::class,
            'pipeline_uuid',
            'uuid',
            'uuid',
            'task_uuid'
        );
    }
}
