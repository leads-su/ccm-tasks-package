<?php

namespace ConsulConfigManager\Tasks\Enums;

/**
 * Class TaskType
 * @package ConsulConfigManager\Tasks\Enums
 */
class TaskType
{
    /**
     * Task Type Constant: Local
     * This task should be executed locally on the server running CCM UI
     * @var integer
     */
    public const LOCAL = 0;

    /**
     * Task Type Constant: Remote
     * This task should be executed remotely
     * @var integer
     */
    public const REMOTE = 1;
}
