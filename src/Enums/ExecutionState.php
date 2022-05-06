<?php

namespace ConsulConfigManager\Tasks\Enums;

/**
 * Class ExecutionState
 * @package ConsulConfigManager\Tasks\Enums
 */
class ExecutionState
{
    /**
     * Execution State Constant: Created
     * Job has been created but nothing else has happened
     * @var integer
     */
    public const CREATED = 0;

    /**
     * Execution State Constant: Waiting
     * Job is now waiting in the execution queue
     * @var integer
     */
    public const WAITING = 1;

    /**
     * Execution State Constant: Executing
     * Job is now executing on the remote host
     * @var integer
     */
    public const EXECUTING = 2;

    /**
     * Execution State Constant: Canceled
     * Job execution has been canceled
     * @var integer
     */
    public const CANCELED = 3;

    /**
     * Execution State Constant: Success
     * Successfully finished job
     * @var integer
     */
    public const SUCCESS = 4;

    /**
     * Execution State Constant: Failure
     * Failed to finish job
     * @var integer
     */
    public const FAILURE = 5;

    /**
     * Execution State Constant: Partially Completed
     * Job contains items which were not successfully completed
     * @var integer
     */
    public const PARTIALLY_COMPLETED = 6;
}
