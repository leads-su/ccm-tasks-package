<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Interface ActionInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface ActionInterface extends SourcedInterface
{
    /**
     * Get action instance by UUID
     * @param string $uuid
     * @param bool $withTrashed
     * @return ActionInterface|null
     */
    public static function uuid(string $uuid, bool $withTrashed = false): ?ActionInterface;

    /**
     * Get action identifier
     * @return int
     */
    public function getID(): int;

    /**
     * Set action identifier
     * @param int $id
     * @return $this
     */
    public function setID(int $id): self;

    /**
     * Get action uuid
     * @return string
     */
    public function getUuid(): string;

    /**
     * Set action uuid
     * @param string $uuid
     * @return $this
     */
    public function setUuid(string $uuid): self;

    /**
     * Get action name
     * @return string
     */
    public function getName(): string;

    /**
     * Set action name
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self;

    /**
     * Get action description
     * @return string
     */
    public function getDescription(): string;

    /**
     * Set action description
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self;

    /**
     * Get action type
     * @return int
     */
    public function getType(): int;

    /**
     * Set action type
     * @param int $type
     * @return $this
     */
    public function setType(int $type): self;

    /**
     * Get action command
     * @return string
     */
    public function getCommand(): string;

    /**
     * Set action command
     * @param string $command
     * @return $this
     */
    public function setCommand(string $command): self;

    /**
     * Get action arguments
     * @return array
     */
    public function getArguments(): array;

    /**
     * Set action arguments
     * @param array $arguments
     * @return $this
     */
    public function setArguments(array $arguments): self;

    /**
     * Get working (root) directory for action execution
     * @return string|null
     */
    public function getWorkingDirectory(): ?string;

    /**
     * Set working (root) directory for action execution
     * @param string|null $workingDirectory
     * @return $this
     */
    public function setWorkingDirectory(?string $workingDirectory = null): self;

    /**
     * Get user from whom this action should be executed
     * @return string|null
     */
    public function getRunAs(): ?string;

    /**
     * Set user from whom this action should be executed
     * @param string|null $runAs
     * @return $this
     */
    public function setRunAs(?string $runAs = null): self;

    /**
     * Check whether action should be executed with sudo privileges
     * @return bool
     */
    public function isUsingSudo(): bool;

    /**
     * Set whether action should be executed with sudo privileges
     * @param bool $useSudo
     * @return $this
     */
    public function useSudo(bool $useSudo): self;

    /**
     * Check whether action should fail on error
     * @return bool
     */
    public function isFailingOnError(): bool;

    /**
     * Set whether action should fail on error
     * @param bool $failOnError
     * @return $this
     */
    public function failOnError(bool $failOnError): self;

    /**
     * Get list of hosts bound to action
     * @return HasManyThrough
     */
    public function hosts(): HasManyThrough;

    /**
     * Get list of servers bound to this action as relations
     * @return HasMany
     */
    public function servers(): HasMany;

    /**
     * Get list of servers bound to this action as array
     * @return array
     */
    public function getServersAttribute(): array;

    /**
     * Get list of servers bound to this action as array with extended information
     * @return array
     */
    public function getServersExtendedAttribute(): array;
}
