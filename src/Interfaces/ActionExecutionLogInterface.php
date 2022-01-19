<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interface ActionExecutionLogInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface ActionExecutionLogInterface
{
    /**
     * Get log identifier
     * @return int
     */
    public function getID(): int;

    /**
     * Set log identifier
     * @param int $identifier
     * @return ActionExecutionLogInterface
     */
    public function setID(int $identifier): ActionExecutionLogInterface;

    /**
     * Set action execution identifier
     * @param int $identifier
     * @return ActionExecutionLogInterface
     */
    public function setActionExecutionID(int $identifier): ActionExecutionLogInterface;

    /**
     * Get action execution identifier
     * @return int
     */
    public function getActionExecutionID(): int;

    /**
     * Set exit code
     * @param int $exitCode
     * @return ActionExecutionLogInterface
     */
    public function setExitCode(int $exitCode): ActionExecutionLogInterface;

    /**
     * Get exit code
     * @return int
     */
    public function getExitCode(): int;

    /**
     * Set output
     * @param array $output
     * @return ActionExecutionLogInterface
     */
    public function setOutput(array $output): ActionExecutionLogInterface;

    /**
     * Get output
     * @return array
     */
    public function getOutput(): array;

    /**
     * Get Action Execution instance from Action Execution Log
     * @return BelongsTo
     */
    public function actionExecution(): BelongsTo;
}
