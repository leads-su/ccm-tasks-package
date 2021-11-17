<?php

namespace ConsulConfigManager\Tasks\Enums;

/**
 * Class ActionType
 * @package ConsulConfigManager\Tasks\Enums
 */
class ActionType
{
    /**
     * Action Type Constant: Local
     * This action should be executed locally on the server running CCM UI
     * @var integer
     */
    public const LOCAL = 0;

    /**
     * Action Type Constant: Local
     * This action should be executed remotely
     * @var integer
     */
    public const REMOTE = 1;
}
