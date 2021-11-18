<?php

namespace ConsulConfigManager\Tasks\Events\Action;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;

/**
 * Class ActionUpdated
 * @package ConsulConfigManager\Tasks\Events\Action
 */
class ActionUpdated extends AbstractEvent
{
    /**
     * Action name
     * @var string
     */
    private string $name;

    /**
     * Action description
     * @var string
     */
    private string $description;

    /**
     * Action type
     * @var int
     */
    private int $type;

    /**
     * Action command
     * @var string
     */
    private string $command;

    /**
     * Action arguments
     * @var array
     */
    private array $arguments;

    /**
     * Action working directory
     * @var string|null
     */
    private ?string $workingDirectory;

    /**
     * Run this action as specified user
     * @var string|null
     */
    private ?string $runAs;

    /**
     * Indicates whether SUDO should be used
     * @var bool
     */
    private bool $useSudo;

    /**
     * Indicates whether action should fail on error
     * @var bool
     */
    private bool $failOnError;

    /**
     * ActionUpdated constructor.
     * @param string $name
     * @param string $description
     * @param int $type
     * @param string $command
     * @param array $arguments
     * @param string|null $workingDirectory
     * @param string|null $runAs
     * @param bool $useSudo
     * @param bool $failOnError
     * @param UserEntity|int|null $user
     * @return void
     */
    public function __construct(
        string $name,
        string $description,
        int $type,
        string $command,
        array $arguments,
        ?string $workingDirectory = null,
        ?string $runAs = null,
        bool $useSudo = false,
        bool $failOnError = true,
        UserEntity|int|null $user = null,
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->type = $type;
        $this->command = $command;
        $this->arguments = $arguments;
        $this->workingDirectory = $workingDirectory;
        $this->runAs = $runAs;
        $this->useSudo = $useSudo;
        $this->failOnError = $failOnError;
        $this->user = $user;
        parent::__construct();
    }

    /**
     * Get action name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get action description
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get action type
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Get action command
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * Get action arguments
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Get action working directory
     * @return string|null
     */
    public function getWorkingDirectory(): ?string
    {
        return $this->workingDirectory;
    }

    /**
     * Get user who is used to run this action
     * @return string|null
     */
    public function getRunAs(): ?string
    {
        return $this->runAs;
    }

    /**
     * Check whether action requires SUDO privileges
     * @return bool
     */
    public function isUsingSudo(): bool
    {
        return $this->useSudo;
    }

    /**
     * Check if action will fail if error is occurred
     * @return bool
     */
    public function isFailingOnError(): bool
    {
        return $this->failOnError;
    }
}
