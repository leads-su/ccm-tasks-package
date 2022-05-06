<?php

namespace ConsulConfigManager\Tasks\Exceptions;

use RuntimeException;

// @codeCoverageIgnoreStart

/**
 * Class InvalidLocalTaskCommandException
 * @package ConsulConfigManager\Tasks\Exceptions
 */
class InvalidLocalTaskCommandException extends RuntimeException
{
    /**
     * InvalidLocalTaskCommandException constructor.
     * @param string $command
     * @param array $supportedCommands
     * @return void
     */
    public function __construct(string $command, array $supportedCommands = ['artisan', 'curl'])
    {
        parent::__construct(
            sprintf(
                'Command: `%s` is not on the list of supported command for local task: %s',
                $command,
                implode(',', $supportedCommands),
            ),
            0,
            null
        );
    }
}

// @codeCoverageIgnoreEnd
