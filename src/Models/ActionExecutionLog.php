<?php

namespace ConsulConfigManager\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ConsulConfigManager\Tasks\Factories\ActionExecutionLogFactory;
use ConsulConfigManager\Tasks\Interfaces\ActionExecutionLogInterface;

/**
 * Class ActionExecutionLog
 * @package ConsulConfigManager\Tasks\Models
 *
 * @property ActionExecution actionExecution
 */
class ActionExecutionLog extends Model implements ActionExecutionLogInterface
{
    use HasFactory;

    /**
     * @inheritDoc
     */
    public $table = 'action_execution_logs';

    /**
     * @inheritDoc
     */
    public $fillable = [
        'action_execution_id',
        'exit_code',
        'output',
    ];

    /**
     * @inheritDoc
     */
    public $casts = [
        'id'                        =>  'integer',
        'action_execution_id'       =>  'integer',
        'exit_code'                 =>  'integer',
        'output'                    =>  'array',
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
    protected static function newFactory(): Factory
    {
        return ActionExecutionLogFactory::new();
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
    public function setID(int $identifier): ActionExecutionLogInterface
    {
        $this->attributes['id'] = (int) $identifier;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setActionExecutionID(int $identifier): ActionExecutionLogInterface
    {
        $this->attributes['action_execution_id'] = (int) $identifier;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getActionExecutionID(): int
    {
        return (int) $this->attributes['action_execution_id'];
    }

    /**
     * @inheritDoc
     */
    public function setExitCode(int $exitCode): ActionExecutionLogInterface
    {
        $this->attributes['exit_code'] = (int) $exitCode;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getExitCode(): int
    {
        return (int) $this->attributes['exit_code'];
    }

    /**
     * @inheritDoc
     */
    public function setOutput(array $output): ActionExecutionLogInterface
    {
        $this->attributes['output'] = (string) json_encode($output);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getOutput(): array
    {
        return (array) json_decode($this->attributes['output'], true);
    }

    /**
     * @inheritDoc
     */
    public function actionExecution(): BelongsTo
    {
        return $this->belongsTo(
            ActionExecution::class,
            'action_execution_id',
            'id'
        );
    }
}
